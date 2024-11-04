<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Chat List</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Container styling */
        .chat-box {

            background-color: #fefefe;

            overflow: hidden;


        }

        /* Header styling */
        .chat-header {
            background-color: #c7925c;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            position: relative;
        }

        .chat-header i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .chat-header .back-icon {
            left: 15px;
        }

        .chat-header .filter-icon {
            right: 15px;
        }

        /* Search bar */
        .search-bar {
            margin: 10px 20px;
            display: flex;
            align-items: center;
            background-color: #f0f0f0;
            border-radius: 20px;
            padding: 8px 12px;
        }

        .search-bar input {
            border: none;
            background: none;
            width: 100%;
            outline: none;
            font-size: 14px;
        }

        /* Tab navigation */
        .tab-nav {
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            font-size: 14px;
        }

        .tab-nav div {
            cursor: pointer;
        }

        /* Chat list styling */
        .chat-list {
            max-height: 500px;
            overflow-y: auto;
            padding: 10px;
        }

        .chat-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            transition: background 0.3s;
            position: relative;
            border-bottom: 1px solid #f0f0f0;
        }

        .chat-item:hover {
            background-color: #f7f7f7;
        }

        .chat-item img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .chat-info {
            flex-grow: 1;
        }

        .chat-info h6 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .chat-info p {
            margin: 0;
            font-size: 13px;
            color: #777;
        }

        .chat-time {
            font-size: 12px;
            color: #999;
            white-space: nowrap;
        }

        .unread-badge {
            background-color: #ff6b6b;
            color: white;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 50%;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        /* Floating Action Button */
        .fab {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background-color: #c7925c;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .chat-box {

            }

            .chat-header {
                padding: 10px;
            }

            .chat-header .back-icon, .chat-header .filter-icon {
                font-size: 14px;
            }

            .search-bar {
                margin: 10px;
                padding: 6px 10px;
            }

            .search-bar input {
                font-size: 12px;
            }

            .tab-nav {
                font-size: 12px;
            }

            .chat-item {
                padding: 10px;
            }

            .chat-item img {
                width: 40px;
                height: 40px;
            }

            .chat-info h6 {
                font-size: 14px;
            }

            .chat-info p {
                font-size: 12px;
            }

            .chat-time {
                font-size: 10px;
            }

            .fab {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
            .back-icon {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: white;
    text-decoration: none; /* Remove underline */
}
        }
    </style>
</head>
<body>
    <!-- Chat Box Container -->
    <div class="chat-box">
        <!-- Header -->
      <!-- Header -->
<div class="chat-header">
    <a href="{{ url('/') }}" class="back-icon">
        <i class="fas fa-arrow-left"></i>
    </a>
    MESSAGES
    <i class="fas fa-sliders-h filter-icon"></i>
</div>

        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" placeholder="Search">
            <i class="fas fa-search"></i>
        </div>


        <!-- Chat List -->
        <div class="chat-list">

            <!-- Chat Item -->
            @foreach ($users as $user)
            <div class="chat-item">
                <img src="https://via.placeholder.com/50" alt="Profile Picture">
                <div class="chat-info">
                    <h6>{{ $user->name }}</h6>
                    <p>{{ $user->last_message_time ?? 'N/A' }}</p>
                </div>
                <div class="chat-time">12:31 am</div>
                @if($user->unread_messages_count > 0)
                <span class="unread-badge">3</span>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Floating Action Button -->
        <div class="fab" title="Start new chat">
            <i class="fas fa-plus"></i>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
