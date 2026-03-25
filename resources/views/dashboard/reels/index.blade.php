{{-- resources/views/dashboard/reels/index.blade.php --}}
@extends('dashboard.layouts.app')
@section('title', 'Reels')

@push('styles')
    <style>
        body {
            background: #000 !important;
        }

        .top-nav,
        .bottom-nav {
            z-index: 200;
        }

        .top-nav {
            background: rgba(0, 0, 0, 0.7) !important;
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
        }

        .top-nav .brand {
            background: linear-gradient(135deg, #FF6B6B, #FF8E53, #FFE66D) !important;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .top-nav .coins-pill {
            background: rgba(255, 255, 255, 0.15) !important;
            color: white !important;
        }

        .top-nav .avatar-btn {
            background: rgba(255, 255, 255, 0.2) !important;
        }

        .reels-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow-y: scroll;
            scroll-snap-type: y mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            background: #000;
        }

        .reels-container::-webkit-scrollbar {
            display: none;
        }

        .reel-slide {
            position: relative;
            width: 100%;
            height: 100dvh;
            scroll-snap-align: start;
            scroll-snap-stop: always;
            overflow: hidden;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reel-media {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
        }

        .reel-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .reel-overlay-top {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 120px;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.55), transparent);
            z-index: 5;
            pointer-events: none;
        }

        .reel-overlay-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 220px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.75), transparent);
            z-index: 5;
            pointer-events: none;
        }

        .reel-topbar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 52px 16px 12px;
            z-index: 10;
        }

        .reel-tab-label {
            color: white;
            font-family: 'Fredoka One', cursive;
            font-size: 1.2rem;
            letter-spacing: .5px;
        }

        .notif-btn {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
        }

        .notif-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: white;
        }

        .notif-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #FF6B6B;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.62rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #000;
        }

        /* ── User info bottom-left ── */
        .reel-user-info {
            position: absolute;
            bottom: 90px;
            left: 16px;
            right: 80px;
            z-index: 10;
        }

        .reel-user-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .reel-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.8);
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: white;
            font-size: 0.9rem;
            flex-shrink: 0;
            overflow: hidden;
            cursor: pointer;
            text-decoration: none;
        }

        .reel-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .reel-username {
            color: white;
            font-weight: 900;
            font-size: 0.9rem;
            cursor: pointer;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
            text-decoration: none;
        }

        .reel-follow-btn {
            background: transparent;
            border: 1.5px solid white;
            color: white;
            border-radius: 20px;
            padding: 3px 14px;
            font-size: 0.72rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .25s;
            margin-left: 8px;
            font-family: 'Nunito', sans-serif;
        }

        .reel-follow-btn.following {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .reel-caption {
            color: rgba(255, 255, 255, 0.92);
            font-size: 0.84rem;
            font-weight: 600;
            line-height: 1.45;
            max-height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
            cursor: pointer;
        }

        .reel-caption.expanded {
            max-height: none;
            -webkit-line-clamp: unset;
        }

        .reel-audio {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* ── Action buttons right ── */
        .reel-actions {
            position: absolute;
            right: 12px;
            bottom: 90px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 18px;
            z-index: 10;
        }

        .reel-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            background: none;
            border: none;
            cursor: pointer;
            color: white;
            transition: transform .2s;
        }

        .reel-action-btn:active {
            transform: scale(0.88);
        }

        .action-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            transition: all .3s;
        }

        .reel-action-btn.liked .action-icon {
            background: rgba(255, 107, 107, 0.35);
        }

        .action-count {
            font-size: 0.68rem;
            font-weight: 800;
            color: white;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        .pause-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 8;
            pointer-events: none;
            opacity: 0;
            transition: opacity .3s;
        }

        .pause-overlay.show {
            opacity: 1;
        }

        .pause-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        /* ── Upload FAB ── */
        .upload-fab {
            position: fixed;
            bottom: calc(var(--bottom-h, 60px) + 16px);
            right: 16px;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            color: white;
            font-size: 1.5rem;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.45);
            z-index: 100;
            transition: all .3s;
        }

        .upload-fab:hover {
            transform: scale(1.1);
        }

        /* ── Upload Modal ── */
        .upload-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(6px);
            z-index: 1000;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }

        .upload-modal.open {
            display: flex;
        }

        .upload-sheet {
            background: white;
            border-radius: 28px 28px 0 0;
            width: 100%;
            max-width: 500px;
            padding: 24px 20px 32px;
            animation: slideUpSheet .35s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes slideUpSheet {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }

        .upload-sheet h3 {
            font-family: 'Fredoka One', cursive;
            font-size: 1.3rem;
            margin-bottom: 16px;
            text-align: center;
        }

        .drop-zone {
            border: 2.5px dashed #E5E7EB;
            border-radius: 18px;
            padding: 32px 16px;
            text-align: center;
            cursor: pointer;
            transition: all .25s;
            margin-bottom: 14px;
            background: #FFF8F0;
        }

        .drop-zone:hover,
        .drop-zone.drag-over {
            border-color: #FF6B6B;
            background: rgba(255, 107, 107, 0.05);
        }

        .drop-zone input {
            display: none;
        }

        .dz-icon {
            font-size: 2.5rem;
            margin-bottom: 8px;
        }

        .dz-text {
            font-weight: 800;
            font-size: 0.9rem;
            color: #1A1A2E;
        }

        .dz-sub {
            font-size: 0.75rem;
            color: #6B7280;
            font-weight: 600;
            margin-top: 4px;
        }

        #uploadPreview {
            display: none;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 14px;
            position: relative;
        }

        #uploadPreview video,
        #uploadPreview img {
            width: 100%;
            max-height: 260px;
            object-fit: cover;
            display: block;
        }

        #removePreview {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-field {
            margin-bottom: 12px;
        }

        .form-field label {
            display: block;
            font-size: 0.76rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .3px;
            margin-bottom: 5px;
            color: #1A1A2E;
        }

        .form-field textarea,
        .form-field input[type=text] {
            width: 100%;
            background: #FFF8F0;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            padding: 11px 14px;
            font-family: 'Nunito', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            outline: none;
            resize: none;
            transition: border-color .25s;
            box-sizing: border-box;
        }

        .form-field textarea:focus,
        .form-field input:focus {
            border-color: #FF6B6B;
        }

        .btn-upload-submit {
            width: 100%;
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 13px;
            font-family: 'Nunito', sans-serif;
            font-size: 0.95rem;
            font-weight: 900;
            cursor: pointer;
            transition: all .3s;
            margin-top: 4px;
        }

        .btn-upload-submit:disabled {
            opacity: .5;
            cursor: default;
        }

        /* ── Comment Sheet ── */
        .comment-sheet {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 900;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }

        .comment-sheet.open {
            display: flex;
        }

        .comment-inner {
            background: white;
            border-radius: 24px 24px 0 0;
            width: 100%;
            max-width: 500px;
            height: 72vh;
            display: flex;
            flex-direction: column;
            animation: slideUpSheet .3s ease;
        }

        .comment-header {
            padding: 16px 20px 12px;
            border-bottom: 1.5px solid #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .comment-header h4 {
            font-family: 'Fredoka One', cursive;
            font-size: 1.1rem;
        }

        .comment-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #6B7280;
        }

        .comment-list {
            flex: 1;
            overflow-y: auto;
            padding: 12px 16px;
        }

        .comment-item {
            display: flex;
            gap: 10px;
            margin-bottom: 14px;
            align-items: flex-start;
        }

        .c-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            flex-shrink: 0;
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: white;
            font-size: 0.8rem;
            overflow: hidden;
        }

        .c-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .c-body {
            flex: 1;
        }

        .c-name {
            font-size: 0.78rem;
            font-weight: 900;
            color: #1A1A2E;
            margin-bottom: 2px;
        }

        .c-text {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1A1A2E;
            line-height: 1.4;
        }

        .c-time {
            font-size: 0.68rem;
            color: #6B7280;
            font-weight: 700;
            margin-top: 3px;
        }

        .comment-input-row {
            padding: 12px 16px;
            border-top: 1.5px solid #E5E7EB;
            flex-shrink: 0;
            display: flex;
            gap: 10px;
            align-items: center;
            padding-bottom: max(12px, env(safe-area-inset-bottom));
        }

        .comment-input-row textarea {
            flex: 1;
            background: #FFF8F0;
            border: 2px solid #E5E7EB;
            border-radius: 20px;
            padding: 10px 14px;
            font-family: 'Nunito', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            outline: none;
            resize: none;
            max-height: 80px;
            min-height: 40px;
            transition: border-color .25s;
        }

        .comment-input-row textarea:focus {
            border-color: #FF6B6B;
        }

        .comment-send-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            color: white;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all .25s;
        }

        /* ── Notification sheet ── */
        .notif-sheet {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 950;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }

        .notif-sheet.open {
            display: flex;
        }

        .notif-inner {
            background: white;
            border-radius: 24px 24px 0 0;
            width: 100%;
            max-width: 500px;
            height: 70vh;
            display: flex;
            flex-direction: column;
            animation: slideUpSheet .3s ease;
        }

        .notif-header {
            padding: 16px 20px 12px;
            border-bottom: 1.5px solid #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .notif-header h4 {
            font-family: 'Fredoka One', cursive;
            font-size: 1.1rem;
        }

        .notif-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px 0;
        }

        .notif-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            transition: background .2s;
            cursor: pointer;
        }

        .notif-item:hover {
            background: #FFF8F0;
        }

        .notif-item.unread {
            background: rgba(255, 107, 107, 0.05);
        }

        .n-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            flex-shrink: 0;
            background: linear-gradient(135deg, #FF6B6B, #FF8E53);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: white;
            font-size: 0.95rem;
            overflow: hidden;
        }

        .n-body {
            flex: 1;
        }

        .n-text {
            font-size: 0.84rem;
            font-weight: 700;
            color: #1A1A2E;
            line-height: 1.4;
        }

        .n-time {
            font-size: 0.7rem;
            color: #6B7280;
            font-weight: 600;
            margin-top: 2px;
        }

        .n-dot {
            width: 8px;
            height: 8px;
            background: #FF6B6B;
            border-radius: 50%;
            flex-shrink: 0;
        }

        @keyframes heartBurst {
            0% {
                transform: scale(0) rotate(-30deg);
                opacity: 1;
            }

            50% {
                transform: scale(1.6) rotate(10deg);
                opacity: 1;
            }

            100% {
                transform: scale(0) rotate(20deg);
                opacity: 0;
            }
        }

        .heart-burst {
            position: absolute;
            font-size: 3rem;
            pointer-events: none;
            z-index: 20;
            animation: heartBurst .6s ease-out forwards;
        }

        .reel-skeleton {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, #111 25%, #222 50%, #111 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('content')
    <div style="position:fixed;inset:0;z-index:50;">

        {{-- Top bar --}}
        <div class="reel-topbar">
            <div class="reel-tab-label">✨ Reels</div>
            <button class="notif-btn" onclick="openNotifSheet()" id="notifBtn">
                <div class="notif-icon">🔔</div>
                <div class="notif-badge" id="notifBadge" style="display:none;">0</div>
            </button>
        </div>

        {{-- Reels Feed --}}
        <div class="reels-container" id="reelsContainer">
            @forelse($reels as $reel)
                <div class="reel-slide" data-id="{{ $reel->id }}" data-viewed="0">

                    <div class="reel-skeleton" id="skel{{ $reel->id }}"></div>

                    @if ($reel->type === 'video')
                        <video class="reel-media" id="vid{{ $reel->id }}"
                            src="{{ asset('storage/' . $reel->file_path) }}" loop playsinline preload="none"
                            onclick="togglePlay({{ $reel->id }})">
                        </video>
                    @else
                        <img class="reel-image" src="{{ asset('storage/' . $reel->file_path) }}" alt="{{ $reel->caption }}"
                            onload="document.getElementById('skel{{ $reel->id }}').style.display='none'">
                    @endif

                    <div class="pause-overlay" id="pauseOverlay{{ $reel->id }}">
                        <div class="pause-circle">▶️</div>
                    </div>

                    <div class="reel-overlay-top"></div>
                    <div class="reel-overlay-bottom"></div>

                    {{-- ── User info — avatar & username link to that user's profile ── --}}
                    <div class="reel-user-info">
                        <div class="reel-user-row">
                            {{-- Clicking avatar → goes to that user's profile --}}
                            <a href="{{ route('users.profile', $reel->user->username ?? $reel->user->id) }}"
                                class="reel-avatar">
                                @if ($reel->user->avatar)
                                    <img src="{{ $reel->user->avatar_url }}" alt="">
                                @else
                                    {{ strtoupper(substr($reel->user->name, 0, 1)) }}
                                @endif
                            </a>
                            {{-- Clicking username → same --}}
                            <a href="{{ route('users.profile', $reel->user->username ?? $reel->user->id) }}"
                                class="reel-username">
                                {{ $reel->user->username ?? $reel->user->name }}
                            </a>
                            @if ($reel->user_id !== auth()->id())
                                <button
                                    class="reel-follow-btn {{ auth()->user()->isFollowing($reel->user) ? 'following' : '' }}"
                                    onclick="toggleFollow(this, {{ $reel->user_id }})" data-user="{{ $reel->user_id }}">
                                    {{ auth()->user()->isFollowing($reel->user) ? 'Following' : 'Follow' }}
                                </button>
                            @endif
                        </div>

                        @if ($reel->caption)
                            <div class="reel-caption" id="cap{{ $reel->id }}"
                                onclick="this.classList.toggle('expanded')">
                                {{ $reel->caption }}
                            </div>
                        @endif

                        @if ($reel->audio_name)
                            <div class="reel-audio">
                                <span style="animation:spin 3s linear infinite;display:inline-block;">🎵</span>
                                {{ $reel->audio_name }}
                            </div>
                        @endif
                    </div>

                    {{-- Action buttons --}}
                    <div class="reel-actions">
                        <button class="reel-action-btn {{ in_array($reel->id, $likedIds) ? 'liked' : '' }}"
                            id="likeBtn{{ $reel->id }}" onclick="toggleLike(this, {{ $reel->id }})">
                            <div class="action-icon" id="likeIcon{{ $reel->id }}">
                                {{ in_array($reel->id, $likedIds) ? '❤️' : '🤍' }}
                            </div>
                            <span class="action-count" id="likeCount{{ $reel->id }}">
                                {{ number_format($reel->likes_count) }}
                            </span>
                        </button>

                        <button class="reel-action-btn" onclick="openComments({{ $reel->id }})">
                            <div class="action-icon">💬</div>
                            <span class="action-count" id="commentCount{{ $reel->id }}">
                                {{ number_format($reel->comments_count) }}
                            </span>
                        </button>

                        <button class="reel-action-btn" onclick="shareReel({{ $reel->id }})">
                            <div class="action-icon">↗️</div>
                            <span class="action-count" id="shareCount{{ $reel->id }}">
                                {{ number_format($reel->shares_count) }}
                            </span>
                        </button>

                        <div class="reel-action-btn" style="cursor:default;">
                            <div class="action-icon">👁️</div>
                            <span class="action-count">{{ number_format($reel->views_count) }}</span>
                        </div>

                        @if ($reel->user_id === auth()->id())
                            <button class="reel-action-btn" onclick="deleteReel({{ $reel->id }})">
                                <div class="action-icon" style="background:rgba(239,68,68,0.3);">🗑️</div>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    style="color:white;text-align:center;padding:40px 20px;height:100dvh;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <div style="font-size:4rem;margin-bottom:16px;">🎬</div>
                    <h3 style="font-family:'Fredoka One',cursive;font-size:1.4rem;margin-bottom:8px;">No Reels Yet</h3>
                    <p style="opacity:.7;font-weight:600;font-size:0.88rem;">Be the first to upload a reel!</p>
                    <button onclick="openUploadModal()"
                        style="margin-top:20px;background:linear-gradient(135deg,#FF6B6B,#FF8E53);color:white;border:none;border-radius:14px;padding:12px 28px;font-family:'Nunito',sans-serif;font-size:0.92rem;font-weight:900;cursor:pointer;">+
                        Upload Reel</button>
                </div>
            @endforelse
        </div>

        {{-- Upload FAB --}}
        {{-- <button class="upload-fab" onclick="openUploadModal()" title="Upload Reel">＋</button> --}}
    </div>

    {{-- ══ UPLOAD MODAL ══ --}}
    <div class="upload-modal" id="uploadModal">
        <div class="upload-sheet">
            <h3>🎬 New Reel / Meme</h3>

            {{-- NOTE: action points to the CORRECT store route --}}
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf

                <div class="drop-zone" id="dropZone" onclick="document.getElementById('fileInput').click()"
                    ondragover="event.preventDefault();this.classList.add('drag-over')"
                    ondragleave="this.classList.remove('drag-over')" ondrop="handleDrop(event)">
                    <input type="file" name="file" id="fileInput" accept="video/*,image/*"
                        onchange="previewFile(this)">
                    <div class="dz-icon">🎬</div>
                    <div class="dz-text">Tap or drag to upload</div>
                    <div class="dz-sub">Video (MP4, MOV) or Photo · Max 100MB</div>
                </div>

                <div id="uploadPreview">
                    <video id="previewVid" controls style="display:none;"></video>
                    <img id="previewImg" style="display:none;">
                    <button type="button" id="removePreview" onclick="clearPreview()">✕</button>
                </div>

                <div class="form-field">
                    <label>Caption</label>
                    <textarea name="caption" rows="3" placeholder="Write a caption… #hashtag @mention"></textarea>
                </div>

                <div class="form-field">
                    <label>Hashtags</label>
                    <input type="text" name="hashtags" placeholder="#gaming #timepass #fun">
                </div>

                <div id="uploadProgress" style="display:none;margin-bottom:10px;">
                    <div style="background:#E5E7EB;border-radius:10px;height:7px;overflow:hidden;">
                        <div id="progressBar"
                            style="height:100%;background:linear-gradient(90deg,#FF6B6B,#FF8E53);width:0%;transition:width .3s;border-radius:10px;">
                        </div>
                    </div>
                    <p style="font-size:0.75rem;font-weight:700;color:#6B7280;margin-top:5px;text-align:center;"
                        id="progressText">Uploading…</p>
                </div>

                <button type="submit" class="btn-upload-submit" id="uploadSubmitBtn" disabled>
                    🚀 Share Reel
                </button>
            </form>

            <button onclick="closeUploadModal()"
                style="width:100%;margin-top:10px;background:none;border:none;color:#6B7280;font-size:0.85rem;font-weight:800;cursor:pointer;padding:8px;">
                Cancel
            </button>
        </div>
    </div>

    {{-- ══ COMMENT SHEET ══ --}}
    <div class="comment-sheet" id="commentSheet">
        <div class="comment-inner">
            <div class="comment-header">
                <h4>💬 Comments</h4>
                <button class="comment-close" onclick="closeComments()">✕</button>
            </div>
            <div class="comment-list" id="commentList">
                <p style="text-align:center;color:#6B7280;font-weight:700;padding:20px;">Loading…</p>
            </div>
            <div class="comment-input-row">
                <textarea id="commentInput" rows="1" placeholder="Add a comment…"
                    onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();postComment();}"></textarea>
                <button class="comment-send-btn" onclick="postComment()">➤</button>
            </div>
        </div>
    </div>

    {{-- ══ NOTIFICATION SHEET ══ --}}
    <div class="notif-sheet" id="notifSheet">
        <div class="notif-inner">
            <div class="notif-header">
                <h4>🔔 Notifications</h4>
                <button onclick="closeNotifSheet()"
                    style="background:none;border:none;color:#6B7280;font-size:1.1rem;cursor:pointer;">✕</button>
            </div>
            <div class="notif-list" id="notifList">
                <p style="text-align:center;color:#6B7280;font-weight:700;padding:20px;">Loading…</p>
            </div>
        </div>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        // ── Use the named route for storing reels ──
        const STORE_URL = '{{ route('reels.store') }}';
        let activeReelId = null;

        // ── Intersection Observer: auto-play/pause ──
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const slide = entry.target;
                const id = slide.dataset.id;
                const vid = document.getElementById('vid' + id);

                if (entry.isIntersecting && entry.intersectionRatio >= 0.8) {
                    activeReelId = id;
                    const skel = document.getElementById('skel' + id);
                    if (skel) skel.style.display = 'none';

                    if (vid) {
                        vid.play().catch(() => {});
                        document.getElementById('pauseOverlay' + id).classList.remove('show');
                    }
                    if (slide.dataset.viewed === '0') {
                        slide.dataset.viewed = '1';
                        fetch(`/reels/${id}/view`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                'Content-Type': 'application/json'
                            }
                        });
                    }
                } else {
                    if (vid) {
                        vid.pause();
                        vid.currentTime = 0;
                    }
                }
            });
        }, {
            threshold: 0.8
        });

        document.querySelectorAll('.reel-slide').forEach(s => observer.observe(s));

        // ── Toggle play/pause ──
        function togglePlay(id) {
            const vid = document.getElementById('vid' + id);
            const overlay = document.getElementById('pauseOverlay' + id);
            if (!vid) return;
            if (vid.paused) {
                vid.play();
                overlay.classList.remove('show');
            } else {
                vid.pause();
                overlay.classList.add('show');
            }
        }

        // ── Double-tap to like ──
        let lastTap = 0;
        document.querySelectorAll('.reel-slide').forEach(slide => {
            slide.addEventListener('touchend', e => {
                const now = Date.now();
                if (now - lastTap < 300) {
                    const id = slide.dataset.id;
                    const touch = e.changedTouches[0];
                    const heart = document.createElement('div');
                    heart.className = 'heart-burst';
                    heart.textContent = '❤️';
                    heart.style.left = (touch.clientX - 20) + 'px';
                    heart.style.top = (touch.clientY - 20) + 'px';
                    slide.appendChild(heart);
                    setTimeout(() => heart.remove(), 700);
                    const btn = document.getElementById('likeBtn' + id);
                    if (!btn.classList.contains('liked')) toggleLike(btn, id);
                }
                lastTap = now;
            }, {
                passive: true
            });
        });

        // ── Like toggle ──
        function toggleLike(btn, id) {
            fetch(`/reels/${id}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    btn.classList.toggle('liked', data.liked);
                    document.getElementById('likeIcon' + id).textContent = data.liked ? '❤️' : '🤍';
                    document.getElementById('likeCount' + id).textContent = data.likes_count.toLocaleString('en-IN');
                });
        }

        // ── Follow toggle ──
        function toggleFollow(btn, userId) {
            fetch(`/users/${userId}/follow`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.following) {
                        btn.textContent = 'Following';
                        btn.classList.add('following');
                    } else {
                        btn.textContent = 'Follow';
                        btn.classList.remove('following');
                    }
                });
        }

        // ── Share ──
        function shareReel(id) {
            fetch(`/reels/${id}/share`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('shareCount' + id).textContent = data.shares_count;
                    if (navigator.share) {
                        navigator.share({
                            title: 'Check this out on TimePass!',
                            url: data.share_url
                        });
                    } else {
                        navigator.clipboard.writeText(data.share_url);
                        showToast('🔗 Link copied!');
                    }
                });
        }

        // ── Delete ──
        function deleteReel(id) {
            if (!confirm('Delete this reel?')) return;
            fetch(`/reels/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(`.reel-slide[data-id="${id}"]`)?.remove();
                        showToast('🗑️ Reel deleted');
                    }
                });
        }
        // ── Upload modal ──
        function openUploadModal() {
            document.getElementById('uploadModal').classList.add('open');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.remove('open');
            clearPreview();

            document.getElementById('uploadProgress').style.display = 'none';
            document.getElementById('progressBar').style.width = '0%';
        }

        // ── Drag & Drop ──
        function handleDrop(e) {
            e.preventDefault();
            document.getElementById('dropZone').classList.remove('drag-over');

            const file = e.dataTransfer.files[0];
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                document.getElementById('fileInput').files = dt.files;
                previewFile(document.getElementById('fileInput'));
            }
        }

        // ── Preview ──
        function previewFile(input) {
            const file = input.files[0];
            if (!file) return;

            // ✅ Frontend validation (IMPORTANT)
            const allowedTypes = [
                'video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo',
                'image/jpeg', 'image/png', 'image/gif', 'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                showToast('❌ Unsupported file type');
                clearPreview();
                return;
            }

            // Max 100MB
            if (file.size > 100 * 1024 * 1024) {
                showToast('❌ File too large (max 100MB)');
                clearPreview();
                return;
            }

            const isVideo = file.type.startsWith('video/');
            const url = URL.createObjectURL(file);

            document.getElementById('uploadPreview').style.display = 'block';
            document.getElementById('dropZone').style.display = 'none';

            if (isVideo) {
                const v = document.getElementById('previewVid');
                v.src = url;
                v.style.display = 'block';
                document.getElementById('previewImg').style.display = 'none';
            } else {
                const img = document.getElementById('previewImg');
                img.src = url;
                img.style.display = 'block';
                document.getElementById('previewVid').style.display = 'none';
            }

            document.getElementById('uploadSubmitBtn').disabled = false;
        }

        // ── Clear ──
        function clearPreview() {
            document.getElementById('uploadPreview').style.display = 'none';
            document.getElementById('dropZone').style.display = 'block';

            document.getElementById('previewVid').src = '';
            document.getElementById('previewImg').src = '';
            document.getElementById('fileInput').value = '';

            document.getElementById('uploadSubmitBtn').disabled = true;
        }

        // ── Upload ──
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];

            if (!file) {
                showToast('⚠️ Please select a file first');
                return;
            }

            const btn = document.getElementById('uploadSubmitBtn');
            const formData = new FormData(this);

            // ✅ Ensure file is appended
            formData.set('file', file);

            btn.disabled = true;
            document.getElementById('uploadProgress').style.display = 'block';

            const xhr = new XMLHttpRequest();
            xhr.open('POST', STORE_URL);

            xhr.setRequestHeader('X-CSRF-TOKEN', CSRF);
            xhr.setRequestHeader('Accept', 'application/json');

            // ── Progress ──
            xhr.upload.onprogress = function(ev) {
                if (ev.lengthComputable) {
                    const pct = Math.round((ev.loaded / ev.total) * 100);
                    document.getElementById('progressBar').style.width = pct + '%';
                    document.getElementById('progressText').textContent = `Uploading… ${pct}%`;
                }
            };

            // ── Success / Error ──
            xhr.onload = function() {
                console.log("STATUS:", xhr.status);
                console.log("RESPONSE:", xhr.responseText);

                try {
                    const data = JSON.parse(xhr.responseText);

                    if (data.success) {
                        showToast('🎬 Reel uploaded!');
                    } else {
                        showToast('❌ ' + (data.message || 'Upload failed'));
                    }

                } catch (e) {
                    console.error("JSON ERROR:", e);
                    showToast('❌ Server returned invalid response');
                }
            };
            // ── Network Error ──
            xhr.onerror = function() {
                btn.disabled = false;
                showToast('❌ Network error. Try again.');
            };

            xhr.send(formData);
        });

        // ── Comments ──
        let activeCommentReelId = null;

        function openComments(id) {
            activeCommentReelId = id;
            document.getElementById('commentSheet').classList.add('open');
            loadComments(id);
            const vid = document.getElementById('vid' + id);
            if (vid && !vid.paused) vid.pause();
        }

        function closeComments() {
            document.getElementById('commentSheet').classList.remove('open');
            const vid = document.getElementById('vid' + activeCommentReelId);
            if (vid) vid.play().catch(() => {});
        }

        function loadComments(id) {
            fetch(`/reels/${id}/comments`)
                .then(r => r.json())
                .then(data => {
                    const list = document.getElementById('commentList');
                    const items = data.data ?? data;
                    if (!items.length) {
                        list.innerHTML =
                            '<p style="text-align:center;color:#6B7280;font-weight:700;padding:20px;">No comments yet. Be first! 💬</p>';
                        return;
                    }
                    list.innerHTML = items.map(c => `
      <div class="comment-item">
        <div class="c-avatar">${c.user.avatar ? `<img src="${c.user.avatar_url}">` : c.user.name[0].toUpperCase()}</div>
        <div class="c-body">
          <div class="c-name">${escHtml(c.user.username ?? c.user.name)}</div>
          <div class="c-text">${escHtml(c.body)}</div>
          <div class="c-time">${c.created_at ?? 'Just now'}</div>
        </div>
      </div>
    `).join('');
                });
        }

        function postComment() {
            const inp = document.getElementById('commentInput');
            const body = inp.value.trim();
            if (!body || !activeCommentReelId) return;

            fetch(`/reels/${activeCommentReelId}/comments`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        body
                    })
                })
                .then(r => r.json())
                .then(data => {
                    inp.value = '';
                    const c = data.comment;
                    const el = document.createElement('div');
                    el.className = 'comment-item';
                    el.innerHTML = `
      <div class="c-avatar">${c.user_avatar ? `<img src="${c.user_avatar}">` : c.user_name[0].toUpperCase()}</div>
      <div class="c-body">
        <div class="c-name">${escHtml(c.user_name)}</div>
        <div class="c-text">${escHtml(c.body)}</div>
        <div class="c-time">Just now</div>
      </div>`;
                    document.getElementById('commentList').prepend(el);
                    document.getElementById('commentCount' + activeCommentReelId).textContent = data.comments_count;
                });
        }

        // ── Notifications ──
        function openNotifSheet() {
            document.getElementById('notifSheet').classList.add('open');
            loadNotifications();
        }

        function closeNotifSheet() {
            document.getElementById('notifSheet').classList.remove('open');
        }

        function loadNotifications() {
            fetch('/notifications')
                .then(r => r.json())
                .then(data => {
                    const list = document.getElementById('notifList');
                    const items = data.data ?? data;
                    if (!items.length) {
                        list.innerHTML =
                            '<p style="text-align:center;color:#6B7280;font-weight:700;padding:20px;">No notifications yet!</p>';
                        return;
                    }
                    list.innerHTML = items.map(n => `
      <div class="notif-item ${n.is_read ? '' : 'unread'}">
        <div class="n-avatar">${n.actor?.name?.[0]?.toUpperCase() ?? '🔔'}</div>
        <div class="n-body">
          <div class="n-text">${escHtml(n.body)}</div>
          <div class="n-time">${n.created_at ?? ''}</div>
        </div>
        ${!n.is_read ? '<div class="n-dot"></div>' : ''}
      </div>
    `).join('');
                    fetch('/notifications/read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF
                        }
                    });
                    document.getElementById('notifBadge').style.display = 'none';
                });
        }

        // Load unread count on mount
        fetch('/notifications/count').then(r => r.json()).then(d => {
            if (d.count > 0) {
                const badge = document.getElementById('notifBadge');
                badge.style.display = 'flex';
                badge.textContent = d.count > 9 ? '9+' : d.count;
            }
        }).catch(() => {});

        // ── Toast ──
        function showToast(msg) {
            let t = document.getElementById('globalToast');
            if (!t) {
                t = document.createElement('div');
                t.id = 'globalToast';
                t.style.cssText =
                    'position:fixed;top:80px;left:50%;transform:translateX(-50%) translateY(-20px);background:linear-gradient(135deg,#10B981,#4ECDC4);color:white;padding:11px 22px;border-radius:30px;font-weight:900;font-size:0.85rem;z-index:9999;opacity:0;transition:all .4s;pointer-events:none;white-space:nowrap;box-shadow:0 8px 24px rgba(0,0,0,.2);';
                document.body.appendChild(t);
            }
            t.textContent = msg;
            t.style.opacity = '1';
            t.style.transform = 'translateX(-50%) translateY(0)';
            setTimeout(() => {
                t.style.opacity = '0';
                t.style.transform = 'translateX(-50%) translateY(-20px)';
            }, 2800);
        }

        function escHtml(s) {
            if (!s) return '';
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        // Backdrop close
        document.getElementById('uploadModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeUploadModal();
        });
        document.getElementById('commentSheet').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeComments();
        });
        document.getElementById('notifSheet').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeNotifSheet();
        });
    </script>
@endsection
