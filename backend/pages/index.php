<?php
// CareBox - Main Entry Point (Landing Page with Gift Animation)
require_once __DIR__ . '/../includes/functions.php';

// If already logged in, redirect to dashboard
if (isDonorLoggedIn()) {
    header("Location: donor_dashboard.php");
    exit();
}
if (isAdminLoggedIn()) {
    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareBox - Gift of Giving</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        .gift-container {
            perspective: 1000px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .gift-container:hover {
            transform: scale(1.05);
        }

        .gift-box {
            width: 200px;
            height: 200px;
            background: #00A3E0;
            position: relative;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            animation: bounce 2s infinite ease-in-out;
        }

        .gift-lid {
            width: 220px;
            height: 50px;
            background: #0088CC;
            position: absolute;
            top: -10px;
            left: -10px;
            z-index: 2;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .ribbon-v, .ribbon-h {
            background: #6D28D9;
            position: absolute;
            z-index: 1;
        }

        .ribbon-v { width: 40px; height: 100%; left: 80px; }
        .ribbon-h { width: 100%; height: 40px; top: 80px; }

        .bow {
            position: absolute;
            top: -40px;
            left: 70px;
            width: 60px;
            height: 40px;
            z-index: 3;
            transition: opacity 0.2s;
        }

        .bow::before, .bow::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border: 8px solid #6D28D9;
            border-radius: 50% 50% 0 50%;
            transform: rotate(45deg);
        }

        .bow::after {
            left: 20px;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
        }

        .gift-container.open .gift-lid {
            transform: translateY(-60px) rotate(-10deg);
            opacity: 0;
        }

        .gift-container.open .bow { opacity: 0; }

        .gift-container.open .gift-box {
            animation: none;
            transform: scale(0.95);
            opacity: 0.7;
            transition: all 0.3s;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        #message-box {
            z-index: 1000;
            transition: opacity 0.3s ease-in-out;
        }

        .text-huge { font-size: 4rem; }
        @media (min-width: 768px) { .text-huge { font-size: 8rem; } }
    </style>
</head>
<body>
    <!-- Message Box -->
    <div id="message-box" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center hidden opacity-0">
        <div class="bg-white p-6 rounded-2xl shadow-2xl max-w-sm w-full">
            <h3 id="message-title" class="text-xl font-bold mb-3 text-red-600"></h3>
            <p id="message-body" class="text-gray-700 mb-4"></p>
            <button onclick="hideMessage()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">Okay</button>
        </div>
    </div>

    <!-- Main Application Container -->
    <div id="app" class="bg-gray-100 min-h-screen">
        <!-- Page 1: Landing Page with Gift Animation -->
        <div id="home-page" class="flex flex-col items-center justify-center min-h-screen px-4 py-12 text-center">
            <h1 class="text-6xl md:text-8xl font-black text-gray-900 mb-2 tracking-tighter">CareBox</h1>
            <p class="text-xl md:text-2xl text-blue-600 font-medium mb-12">Care that Clicks, Help that Sticks</p>
            
            <!-- Gift Box Interaction -->
            <div class="mt-4 mb-16">
                <div id="gift-trigger" class="gift-container mx-auto" onclick="openGift()">
                    <div class="gift-lid">
                        <div class="bow"></div>
                        <div class="ribbon-h" style="top: 10px; height: 30px;"></div>
                        <div class="ribbon-v" style="left: 90px; width: 40px;"></div>
                    </div>
                    <div class="gift-box">
                        <div class="ribbon-v"></div>
                        <div class="ribbon-h"></div>
                    </div>
                </div>
            </div>

            <!-- Why Choose CareBox? -->
            <div class="mt-8 pt-8 w-full max-w-6xl">
                <h2 class="text-3xl font-bold text-gray-800 mb-10">Why Choose CareBox?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="p-6 bg-green-50 rounded-xl shadow-md transition hover:shadow-xl">
                        <svg class="w-10 h-10 mx-auto text-green-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8zm0 0v-8"></path>
                        </svg>
                        <p class="font-bold text-lg mb-2">Real-time Tracking</p>
                        <p class="text-sm text-gray-600">See exactly where your money goes with live updates and transparent reporting.</p>
                    </div>
                    <div class="p-6 bg-red-50 rounded-xl shadow-md transition hover:shadow-xl">
                        <svg class="w-10 h-10 mx-auto text-red-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 15.918l-3.955-4.004a1.5 1.5 0 010-2.122 1.5 1.5 0 012.122 0L12 14.757l6.833-6.84a1.5 1.5 0 012.122 0 1.5 1.5 0 010 2.122l-7.955 7.955a1.5 1.5 0 01-2.122 0z"></path>
                        </svg>
                        <p class="font-bold text-lg mb-2">Verified Recipients</p>
                        <p class="text-sm text-gray-600">All recipients are thoroughly vetted to ensure your donation reach those who genuinely need help.</p>
                    </div>
                    <div class="p-6 bg-yellow-50 rounded-xl shadow-md transition hover:shadow-xl">
                        <svg class="w-10 h-10 mx-auto text-yellow-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5.375C17.65 5.375 22 9.073 22 14.5c0 3.31-2.8 6.425-6.57 7.05M12 5.375C6.35 5.375 2 9.073 2 14.5c0 3.31 2.8 6.425 6.57 7.05M12 5.375V2m-3.5 16h7"></path>
                        </svg>
                        <p class="font-bold text-lg mb-2">Personalized Causes</p>
                        <p class="text-sm text-gray-600">Discover causes that matter to you with our smart matching system based on your interests.</p>
                    </div>
                    <div class="p-6 bg-blue-50 rounded-xl shadow-md transition hover:shadow-xl">
                        <svg class="w-10 h-10 mx-auto text-blue-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 2.895-3 3.895s1.343 3 3 3m0-8V9m0 2v2m0 2v1"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a2 2 0 00-.638-1.442l-3.32-3.48a2 2 0 00-2.222-.524l-1.397.665a2 2 0 00-1.07.606 2 2 0 01-.73.238l-.02.008a2 2 0 00-1.875 0 2 2 0 01-.73-.238l-1.397-.665a2 2 0 00-2.222.524l-3.32 3.48A2 2 0 005 17.72V5z"></path>
                        </svg>
                        <p class="font-bold text-lg mb-2">Micro-donations</p>
                        <p class="text-sm text-gray-600">Every rupee counts. Start with small amounts and join thousands making a collective impact.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page 2: Role Selection -->
        <div id="role-page" class="hidden flex items-center justify-center w-full min-h-screen p-4 sm:p-6 bg-gray-100">
            <div class="max-w-md w-full bg-white p-10 rounded-xl shadow-2xl text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Login Role Selection</h2>
                <p class="text-gray-600 mb-8">Please select your login type to proceed.</p>
                <div class="space-y-4">
                    <a href="login.php?role=donor" class="w-full flex items-center justify-center space-x-3 bg-green-500 text-white font-semibold py-4 rounded-lg shadow-md transform hover:scale-[1.01] hover:bg-green-600 transition block">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span class="text-lg">Login as Donor</span>
                    </a>
                    <a href="login.php?role=admin" class="w-full flex items-center justify-center space-x-3 bg-red-500 text-white font-semibold py-4 rounded-lg shadow-md hover:bg-red-600 transition transform hover:scale-[1.01] block">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826 3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-lg">Login as Admin</span>
                    </a>
                    <div class="mt-8 text-center">
                        <a href="#" onclick="showHome()" class="text-sm text-blue-600 hover:text-blue-800 transition">← Back to Home Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const MESSAGE_BOX = document.getElementById('message-box');

        function showMessage(title, body, isError = true) {
            document.getElementById('message-title').textContent = title;
            document.getElementById('message-title').className = isError ? 'text-xl font-bold mb-3 text-red-600' : 'text-xl font-bold mb-3 text-green-600';
            document.getElementById('message-body').innerHTML = body;
            MESSAGE_BOX.classList.remove('hidden');
            setTimeout(() => MESSAGE_BOX.classList.remove('opacity-0'), 10);
        }

        function hideMessage() {
            MESSAGE_BOX.classList.add('opacity-0');
            setTimeout(() => MESSAGE_BOX.classList.add('hidden'), 300);
        }

        function openGift() {
            const gift = document.getElementById('gift-trigger');
            if (gift.classList.contains('open')) return;
            gift.classList.add('open');
            setTimeout(() => {
                document.getElementById('home-page').classList.add('hidden');
                document.getElementById('role-page').classList.remove('hidden');
            }, 300);
        }

        function showHome() {
            document.getElementById('role-page').classList.add('hidden');
            document.getElementById('home-page').classList.remove('hidden');
            document.getElementById('gift-trigger').classList.remove('open');
        }
    </script>
</body>
</html>
