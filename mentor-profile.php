<?php
// mentor-profile.php - Mentor Profile & Dashboard
require_once 'config.php';
requireRole('mentor');

// Function to get SVG icon for category
function getCategoryIconSVG($categoryName) {
    $icons = [
        'Programming' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;vertical-align:middle;"><path d="M8 6L4 10L8 14M16 6L20 10L16 14M12 4L10 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'School' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;vertical-align:middle;"><path d="M6 7L12 4L18 7L12 10L6 7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M6 7V14L12 17L18 14V7" stroke="currentColor" stroke-width="2"/></svg>',
        'University' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;vertical-align:middle;"><path d="M4 10L12 6L20 10M12 6V18M9 13H15V18H9V13ZM4 10V16M20 10V16M3 16H21" stroke="currentColor" stroke-width="2"/></svg>',
        'Biology' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;vertical-align:middle;"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/><circle cx="8" cy="14" r="3" stroke="currentColor" stroke-width="2"/><circle cx="16" cy="14" r="3" stroke="currentColor" stroke-width="2"/></svg>',
        'Mathematics' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;vertical-align:middle;"><circle cx="12" cy="12" r="6" stroke="currentColor" stroke-width="2"/><path d="M9 12H15M12 9V15" stroke="currentColor" stroke-width="2"/></svg>',
        'Business' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;vertical-align:middle;"><rect x="6" y="8" width="12" height="12" rx="1" stroke="currentColor" stroke-width="2"/><path d="M10 8V6C10 5 11 4 12 4C13 4 14 5 14 6V8" stroke="currentColor" stroke-width="2"/></svg>',
        'Languages' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;vertical-align:middle;"><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/><path d="M4 12C4 12 7 7 12 7C17 7 20 12 20 12C20 12 17 17 12 17C7 17 4 12 4 12" stroke="currentColor" stroke-width="2"/></svg>',
        'Arts' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;vertical-align:middle;"><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/><circle cx="10" cy="6" r="2" fill="currentColor"/><circle cx="14" cy="9" r="1.5" fill="currentColor"/></svg>',
        'Science' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;vertical-align:middle;"><path d="M9 4V10L6 16C5 18 6 20 8 20H16C18 20 19 18 18 16L15 10V4" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="15" r="1.5" fill="currentColor"/></svg>',
        'Engineering' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;vertical-align:middle;"><circle cx="12" cy="12" r="7" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="2" stroke="currentColor" stroke-width="2"/><path d="M12 5V10M12 14V19M5 12H10M14 12H19" stroke="currentColor" stroke-width="2"/></svg>'
    ];
    return $icons[$categoryName] ?? '📚';
}

$mysqli = getDB();
$user_id = getUserId();

// Get mentor profile
$stmt = $mysqli->prepare("
    SELECT mp.*, u.email 
    FROM mentor_profiles mp 
    JOIN users u ON mp.user_id = u.id 
    WHERE mp.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
$result->free();
$stmt->close();

// Get categories
$result = $mysqli->query("SELECT * FROM categories ORDER BY name");
$categories = $result->fetch_all(MYSQLI_ASSOC);
$result->free();

// Get mentor's selected categories
$stmt = $mysqli->prepare("SELECT category_id FROM mentor_categories WHERE mentor_id = ?");
$stmt->bind_param("i", $profile['id']);
$stmt->execute();
$result = $stmt->get_result();
$selected_categories = [];
while ($row = $result->fetch_assoc()) {
    $selected_categories[] = $row['category_id'];
}
$result->free();
$stmt->close();

$success = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['complete_session'])) {
    $full_name = sanitize($_POST['full_name'] ?? '');
    $bio = $_POST['bio'] ?? '';
    $skills = sanitize($_POST['skills'] ?? '');
    $experience = $_POST['experience'] ?? '';
    $hourly_rate = floatval($_POST['hourly_rate'] ?? 0);
    $selected_cats = $_POST['categories'] ?? [];
    
    $mysqli->begin_transaction();
    
    try {
        // Handle profile image upload
        $profile_image = $profile['profile_image'];
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $new_filename = 'mentor_' . $user_id . '_' . time() . '.' . $ext;
                $upload_path = 'uploads/' . $new_filename;
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                    $profile_image = $upload_path;
                }
            }
        }
        
        // If mentor is approved and making changes, set status back to pending for re-approval
        $new_status = $profile['status'];
        if ($profile['status'] === 'approved') {
            $new_status = 'pending';
            $_SESSION['info'] = 'Profile changes submitted for admin re-approval. You can continue managing sessions.';
        }
        
        // Update mentor profile
        $stmt = $mysqli->prepare("
            UPDATE mentor_profiles 
            SET full_name = ?, bio = ?, skills = ?, experience = ?, 
                hourly_rate = ?, profile_image = ?, status = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param("ssssdssi", $full_name, $bio, $skills, $experience, $hourly_rate, $profile_image, $new_status, $user_id);
        $stmt->execute();
        $stmt->close();
        
        // Update categories
        $stmt = $mysqli->prepare("DELETE FROM mentor_categories WHERE mentor_id = ?");
        $stmt->bind_param("i", $profile['id']);
        $stmt->execute();
        $stmt->close();
        
        if (!empty($selected_cats)) {
            $stmt = $mysqli->prepare("INSERT INTO mentor_categories (mentor_id, category_id) VALUES (?, ?)");
            foreach ($selected_cats as $cat_id) {
                $stmt->bind_param("ii", $profile['id'], $cat_id);
                $stmt->execute();
            }
            $stmt->close();
        }
        
        $mysqli->commit();
        $success = 'Profile updated successfully!';
        
        // Refresh profile
        $stmt = $mysqli->prepare("SELECT * FROM mentor_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $profile = $result->fetch_assoc();
        $result->free();
        $stmt->close();
        
        $stmt = $mysqli->prepare("SELECT category_id FROM mentor_categories WHERE mentor_id = ?");
        $stmt->bind_param("i", $profile['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $selected_categories = [];
        while ($row = $result->fetch_assoc()) {
            $selected_categories[] = $row['category_id'];
        }
        $result->free();
        $stmt->close();
        
    } catch(Exception $e) {
        $mysqli->rollback();
        $error = 'Update failed. Please try again.';
    }
}

// Get mentor statistics
$stmt = $mysqli->prepare("SELECT COUNT(*) as total FROM sessions WHERE mentor_id = ? AND status = 'completed'");
$stmt->bind_param("i", $profile['id']);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();
$total_sessions = $stats['total'];
$result->free();
$stmt->close();

// Handle session completion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_session'])) {
    $session_id = intval($_POST['session_id']);
    $stmt = $mysqli->prepare("UPDATE sessions SET status = 'completed' WHERE id = ? AND mentor_id = ?");
    $stmt->bind_param("ii", $session_id, $profile['id']);
    if ($stmt->execute()) {
        $success = 'Session marked as completed! Mentee can now provide feedback.';
    }
    $stmt->close();
}

// Get upcoming and pending sessions
$stmt = $mysqli->prepare("
    SELECT s.*, mp.full_name as mentee_name, mp.interests
    FROM sessions s
    JOIN mentee_profiles mp ON s.mentee_id = mp.id
    WHERE s.mentor_id = ? AND s.status IN ('pending', 'confirmed')
    ORDER BY s.scheduled_at ASC
");
$stmt->bind_param("i", $profile['id']);
$stmt->execute();
$result = $stmt->get_result();
$pending_sessions = $result->fetch_all(MYSQLI_ASSOC);
$result->free();
$stmt->close();

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Dashboard - MentorBridge</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e1b4b 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        body::-webkit-scrollbar {
            width: 8px;
        }

        body::-webkit-scrollbar-track {
            background: rgba(30, 27, 75, 0.5);
        }

        body::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 4px;
        }

        body::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #8b5cf6 0%, #6366f1 100%);
        }

        /* Floating Gradient Orbs */
        body::before {
            content: '';
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15), transparent 70%);
            border-radius: 50%;
            top: -250px;
            right: -250px;
            animation: float 20s ease-in-out infinite;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15), transparent 70%);
            border-radius: 50%;
            bottom: -200px;
            left: -200px;
            animation: float 15s ease-in-out infinite reverse;
            z-index: 0;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(50px, 50px); }
        }

        .nav-bar {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            padding: 1.25rem 2.5rem;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            box-shadow: 0 0 40px rgba(139, 92, 246, 0.2), inset 0 0 0 1px rgba(139, 92, 246, 0.1);
            position: relative;
            z-index: 100;
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 900;
            background: linear-gradient(135deg, #a78bfa, #c4b5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 20px rgba(139, 92, 246, 0.5));
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .user-badge {
            display: flex;
            align-items: center;
            padding: 0.6rem 1.2rem;
            background: rgba(139, 92, 246, 0.1);
            border: 1.5px solid rgba(139, 92, 246, 0.3);
            border-radius: 12px;
            color: #e0e7ff;
            font-weight: 600;
            font-size: 0.95rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.15);
            transition: all 0.3s ease;
        }

        .user-badge:hover {
            background: rgba(139, 92, 246, 0.15);
            border-color: rgba(139, 92, 246, 0.4);
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.25);
        }

        .user-badge svg {
            flex-shrink: 0;
        }

        .btn {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: 'Inter', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            color: white;
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 50px rgba(139, 92, 246, 0.5);
        }

        .btn-secondary {
            background: rgba(139, 92, 246, 0.15);
            color: #c4b5fd;
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(139, 92, 246, 0.25);
            border-color: rgba(139, 92, 246, 0.5);
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.3);
            transform: translateY(-2px);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .status-banner {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            padding: 1.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 0 40px rgba(139, 92, 246, 0.2), inset 0 0 0 1px rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        .status-pending {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2));
            color: #fbbf24;
            border-color: rgba(251, 191, 36, 0.3);
        }

        .status-approved {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(5, 150, 105, 0.2));
            color: #4ade80;
            border-color: rgba(34, 197, 94, 0.3);
        }

        .status-rejected {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));
            color: #fca5a5;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            padding: 2rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 0 40px rgba(139, 92, 246, 0.2), inset 0 0 0 1px rgba(139, 92, 246, 0.1);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 0 60px rgba(139, 92, 246, 0.4), 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            filter: drop-shadow(0 0 10px rgba(139, 92, 246, 0.5));
        }

        .stat-icon svg {
            width: 3rem;
            height: 3rem;
            stroke: url(#statGradient);
            filter: drop-shadow(0 0 15px rgba(139, 92, 246, 0.6));
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #a78bfa, #c4b5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #94a3b8;
            font-size: 1rem;
        }

        .profile-card {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 0 40px rgba(139, 92, 246, 0.2), inset 0 0 0 1px rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        h2 {
            color: #e0e7ff;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-weight: 800;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.75rem;
            color: #c7d2fe;
            font-weight: 600;
            font-size: 0.9rem;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid rgba(139, 92, 246, 0.2);
            border-radius: 14px;
            font-size: 1rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: 'Inter', sans-serif;
            background: rgba(15, 23, 42, 0.5);
            color: #e0e7ff;
            backdrop-filter: blur(10px);
        }

        input[type="file"] {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid rgba(139, 92, 246, 0.2);
            border-radius: 14px;
            font-size: 1rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: 'Inter', sans-serif;
            background: rgba(15, 23, 42, 0.5);
            color: #e0e7ff;
            backdrop-filter: blur(10px);
            cursor: pointer;
        }

        input[type="file"]::file-selector-button {
            padding: 0.5rem 1rem;
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 8px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            cursor: pointer;
            font-weight: 600;
            margin-right: 1rem;
            transition: all 0.2s ease;
        }

        input[type="file"]::file-selector-button:hover {
            background: linear-gradient(135deg, #7c3aed, #a78bfa);
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.4);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15), 0 0 30px rgba(139, 92, 246, 0.3);
            background: rgba(15, 23, 42, 0.7);
        }

        input::placeholder,
        textarea::placeholder {
            color: #64748b;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }

        .category-checkbox {
            display: flex;
            align-items: center;
            padding: 0;
            border: 2px solid rgba(139, 92, 246, 0.2);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(15, 23, 42, 0.5);
            color: #cbd5e1;
            position: relative;
        }

        .category-checkbox:hover {
            border-color: rgba(139, 92, 246, 0.5);
            background: rgba(139, 92, 246, 0.1);
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.2);
        }

        .category-checkbox:has(input[type="checkbox"]:checked) {
            background: rgba(139, 92, 246, 0.15);
            border-color: rgba(139, 92, 246, 0.6);
        }

        .category-checkbox input[type="checkbox"]:checked + label {
            color: #c4b5fd;
            font-weight: 700;
        }

        .category-checkbox input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        .category-checkbox label {
            width: 100%;
            padding: 1rem;
            cursor: pointer;
            margin: 0;
            text-align: center;
        }

        .profile-image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 16px;
            margin-top: 1rem;
            border: 2px solid rgba(139, 92, 246, 0.3);
        }

        .alert {
            padding: 1.1rem 1.5rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
            font-weight: 500;
            border: 1px solid;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            color: #86efac;
            border-color: rgba(34, 197, 94, 0.3);
            box-shadow: 0 0 30px rgba(34, 197, 94, 0.2);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border-color: rgba(239, 68, 68, 0.3);
            box-shadow: 0 0 30px rgba(239, 68, 68, 0.2);
        }

        @media (max-width: 768px) {
            .nav-bar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem 1.5rem;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="nav-bar">
        <div class="logo">🎓 MentorBridge</div>
        <div class="nav-buttons">
            <div class="user-badge">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;vertical-align:middle;margin-right:0.5rem;">
                    <circle cx="12" cy="8" r="4" stroke="#a78bfa" stroke-width="2"/>
                    <path d="M6 21C6 17.686 8.686 15 12 15C15.314 15 18 17.686 18 21" stroke="#a78bfa" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span><?php echo htmlspecialchars(!empty($profile['full_name']) ? $profile['full_name'] : 'John Doe'); ?></span>
            </div>
            <?php if ($profile['status'] === 'approved'): ?>
                <a href="mentor-dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($_SESSION['info'])): ?>
            <div class="status-banner" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                ℹ️ <?php echo $_SESSION['info']; unset($_SESSION['info']); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($profile['status'] === 'pending' && empty($profile['full_name'])): ?>
            <div class="status-banner status-pending">
                👋 Welcome! Please complete your mentor profile to get started.
            </div>
        <?php elseif ($profile['status'] === 'pending'): ?>
            <div class="status-banner status-pending">
                ⏳ Your profile is pending admin approval. You'll be notified once approved.
            </div>
        <?php elseif ($profile['status'] === 'approved'): ?>
            <div class="status-banner status-approved">
                ✅ Edit your profile here - changes will require admin re-approval but won't interrupt your sessions.
            </div>
        <?php elseif ($profile['status'] === 'rejected'): ?>
            <div class="status-banner status-rejected">
                ❌ Your profile was not approved. Please contact support for more information.
            </div>
        <?php endif; ?>

        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="4" width="18" height="18" rx="2" stroke="#a78bfa" stroke-width="2"/>
                        <path d="M16 2V6M8 2V6M3 10H21" stroke="#a78bfa" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="8" cy="14" r="1" fill="#c4b5fd"/>
                        <circle cx="12" cy="14" r="1" fill="#c4b5fd"/>
                        <circle cx="16" cy="14" r="1" fill="#c4b5fd"/>
                        <circle cx="8" cy="18" r="1" fill="#c4b5fd"/>
                        <circle cx="12" cy="18" r="1" fill="#c4b5fd"/>
                    </svg>
                </div>
                <div class="stat-value"><?php echo $total_sessions; ?></div>
                <div class="stat-label">Completed Sessions</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke="#a78bfa" stroke-width="2" stroke-linejoin="round" fill="#a78bfa" fill-opacity="0.3"/>
                    </svg>
                </div>
                <div class="stat-value"><?php echo number_format($profile['average_rating'], 1); ?></div>
                <div class="stat-label">Average Rating</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 11.5C21 16.75 16.97 21 12 21C10.73 21 9.52 20.75 8.42 20.3L3 21L4.3 16.58C3.52 15.29 3 13.82 3 12.25C3 7 7.03 3 12 3C16.97 3 21 6.75 21 11.5Z" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="8" cy="11.5" r="1" fill="#c4b5fd"/>
                        <circle cx="12" cy="11.5" r="1" fill="#c4b5fd"/>
                        <circle cx="16" cy="11.5" r="1" fill="#c4b5fd"/>
                    </svg>
                </div>
                <div class="stat-value"><?php echo $profile['total_reviews']; ?></div>
                <div class="stat-label">Total Reviews</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="9" stroke="#a78bfa" stroke-width="2"/>
                        <path d="M12 6V18M15 9C15 8.20435 14.6839 7.44129 14.1213 6.87868C13.5587 6.31607 12.7956 6 12 6C11.2044 6 10.4413 6.31607 9.87868 6.87868C9.31607 7.44129 9 8.20435 9 9C9 9.79565 9.31607 10.5587 9.87868 11.1213C10.4413 11.6839 11.2044 12 12 12C12.7956 12 13.5587 12.3161 14.1213 12.8787C14.6839 13.4413 15 14.2044 15 15C15 15.7956 14.6839 16.5587 14.1213 17.1213C13.5587 17.6839 12.7956 18 12 18C11.2044 18 10.4413 17.6839 9.87868 17.1213C9.31607 16.5587 9 15.7956 9 15" stroke="#a78bfa" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="stat-value">$<?php echo number_format($profile['hourly_rate'], 0); ?></div>
                <div class="stat-label">Hourly Rate</div>
            </div>
        </div>

        <div class="profile-card">
            <h2>
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:1.5rem;height:1.5rem;vertical-align:middle;display:inline-block;margin-right:0.5rem;">
                    <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15" stroke="#a78bfa" stroke-width="2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1" stroke="#a78bfa" stroke-width="2"/>
                    <path d="M9 12H15M9 16H15" stroke="#a78bfa" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Your Profile
            </h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;vertical-align:middle;margin-right:0.5rem;">
                            <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="7" r="4" stroke="#a78bfa" stroke-width="2"/>
                        </svg>
                        Full Name
                    </label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($profile['full_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;vertical-align:middle;margin-right:0.5rem;">
                            <rect x="3" y="3" width="18" height="18" rx="2" stroke="#a78bfa" stroke-width="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5" fill="#a78bfa"/>
                            <path d="M21 15L16 10L5 21" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Profile Image
                    </label>
                    <input type="file" name="profile_image" accept="image/*">
                    <?php if ($profile['profile_image']): ?>
                        <img src="<?php echo htmlspecialchars($profile['profile_image']); ?>" alt="Profile" class="profile-image-preview">
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;vertical-align:middle;margin-right:0.5rem;">
                            <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 2V8H20M16 13H8M16 17H8M10 9H8" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Bio (Tell students about yourself)
                    </label>
                    <textarea name="bio" required><?php echo htmlspecialchars($profile['bio']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;vertical-align:middle;margin-right:0.5rem;">
                            <path d="M9 18H15M10 22H14M12 2C10.6155 2 9.26216 2.41054 8.11101 3.17971C6.95987 3.94888 6.06266 5.04213 5.53285 6.32122C5.00303 7.6003 4.86441 9.00776 5.13451 10.3656C5.4046 11.7235 6.07129 12.9708 7.05026 13.9497C7.51808 14.4175 7.87644 14.9842 8.09776 15.6079C8.31909 16.2316 8.39783 16.8966 8.32853 17.5543C8.26862 18.1255 8.5 18.5 9 18.5H15C15.5 18.5 15.7314 18.1255 15.6715 17.5543C15.6022 16.8966 15.6809 16.2316 15.9022 15.6079C16.1236 14.9842 16.4819 14.4175 16.9497 13.9497C17.9287 12.9708 18.5954 11.7235 18.8655 10.3656C19.1356 9.00776 18.997 7.6003 18.4672 6.32122C17.9373 5.04213 17.0401 3.94888 15.889 3.17971C14.7378 2.41054 13.3845 2 12 2Z" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Skills (Comma-separated)
                    </label>
                    <input type="text" name="skills" value="<?php echo htmlspecialchars($profile['skills']); ?>" placeholder="e.g., Python, Machine Learning, Web Development" required>
                </div>

                <div class="form-group">
                    <label>
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;vertical-align:middle;margin-right:0.5rem;">
                            <rect x="2" y="7" width="20" height="14" rx="2" stroke="#a78bfa" stroke-width="2"/>
                            <path d="M16 7V5C16 4.46957 15.7893 3.96086 15.4142 3.58579C15.0391 3.21071 14.5304 3 14 3H10C9.46957 3 8.96086 3.21071 8.58579 3.58579C8.21071 3.96086 8 4.46957 8 5V7M2 12H22" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Experience
                    </label>
                    <textarea name="experience" placeholder="Describe your professional experience"><?php echo htmlspecialchars($profile['experience']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;vertical-align:middle;margin-right:0.5rem;">
                            <circle cx="12" cy="12" r="9" stroke="#a78bfa" stroke-width="2"/>
                            <path d="M12 6V18M15 9C15 8.20435 14.6839 7.44129 14.1213 6.87868C13.5587 6.31607 12.7956 6 12 6C11.2044 6 10.4413 6.31607 9.87868 6.87868C9.31607 7.44129 9 8.20435 9 9C9 9.79565 9.31607 10.5587 9.87868 11.1213C10.4413 11.6839 11.2044 12 12 12C12.7956 12 13.5587 12.3161 14.1213 12.8787C14.6839 13.4413 15 14.2044 15 15C15 15.7956 14.6839 16.5587 14.1213 17.1213C13.5587 17.6839 12.7956 18 12 18C11.2044 18 10.4413 17.6839 9.87868 17.1213C9.31607 16.5587 9 15.7956 9 15" stroke="#a78bfa" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Hourly Rate ($)
                    </label>
                    <input type="number" name="hourly_rate" value="<?php echo $profile['hourly_rate']; ?>" min="0" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>Categories (Select all that apply)</label>
                    <div class="categories-grid">
                        <?php foreach ($categories as $cat): ?>
                            <div class="category-checkbox">
                                <input type="checkbox" 
                                       name="categories[]" 
                                       value="<?php echo $cat['id']; ?>" 
                                       id="cat_<?php echo $cat['id']; ?>"
                                       <?php echo in_array($cat['id'], $selected_categories) ? 'checked' : ''; ?>>
                                <label for="cat_<?php echo $cat['id']; ?>">
                                    <?php echo getCategoryIconSVG($cat['name']) . ' ' . htmlspecialchars($cat['name']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                    Save Profile
                </button>
            </form>
        </div>
    </div>
</body>
</html>