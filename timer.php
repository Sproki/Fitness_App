<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

global $con;
require("autoload.php");

$exercisesId = isset($_GET['exercises']) ? (int)$_GET['exercises'] : null;

if ($exercisesId === null) {
    header("Location: training.php");
    exit;
}

$exercise = null;

$sql = "SELECT * from exercises where id = :id";
$stmt = $con->prepare($sql);
$stmt->execute([':id' => $exercisesId]);
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $exercise = [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'kcal' => $row['kcal'], //ToDo rename
        'image' => $row['exerciseImage'], //ToDo rename
    ];
}

if ($exercise === null) {
    header("Location: training.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
</head>

<header>
    <?php include("components/profile.php"); ?>
    <?php include("components/navbar.html"); ?>
</header>

<body class="z-10 bg-white dark:bg-[#121212]">
<div class="mb-20 mx-[40px] flex flex-col gap-3">
    <h2 class="text-lg font-bold">
        <?= $exercise['title'] ?>
    </h2>
    <p class="text-gray-700 mb-2"> <?= $exercise['description'] ?></p>
    <div class="bg-white p-6 rounded shadow-md">
        <div class="text-center text-4xl font-mono mb-4" id="timerDisplay">00:00</div>
        <div class="flex justify-center space-x-4 mb-4">
            <button id="minusBtn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">- 1 Minute</button>
            <button id="plusBtn" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">+ 1 Minute</button>
        </div>
        <div class="flex justify-center space-x-4 mb-4">
            <button id="startBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Start</button>
            <button id="pauseBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded hidden">Pause</button>
            <button id="resetBtn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded hidden">Reset</button>
        </div>
        <div id="chart"></div>
    </div>
</div>
</body>

<script src="https://cdn.tailwindcss.com"></script>

<script src="scripts/tailwind.config.js"></script>

<script>
    let totalTime = 0;
    let remainingTime = 0;
    let timerInterval = null;

    const options = {
        chart: {
            height: 350,
            type: 'radialBar'
        },
        series: [0],
        plotOptions: {
            radialBar: {
                hollow: {
                    size: '70%'
                },
                dataLabels: {
                    name: {
                        show: false
                    },
                    value: {
                        formatter: function(val) {
                            return parseInt(val) + '%';
                        },
                        fontSize: '24px',
                        offsetY: 10
                    }
                }
            }
        },
        labels: ['Fortschritt']
    };

    const chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

    function formatTime(seconds) {
        const m = Math.floor(seconds / 60).toString().padStart(2, '0');
        const s = (seconds % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    function updateDisplay() {
        document.getElementById('timerDisplay').innerText = formatTime(remainingTime);
    }

    function updateChart() {
        let percentage = 0;
        if(totalTime > 0) {
            percentage = Math.round(((totalTime - remainingTime) / totalTime) * 100);
        }
        chart.updateSeries([percentage]);
    }

    function updateButtonState() {
        const startBtn = document.getElementById("startBtn");
        const pauseBtn = document.getElementById("pauseBtn");
        const plusBtn = document.getElementById("plusBtn");
        const minusBtn = document.getElementById("minusBtn");
        const resetBtn = document.getElementById("resetBtn");

        if (timerInterval === null) {
            startBtn.classList.remove("hidden");
            pauseBtn.classList.add("hidden");
            plusBtn.disabled = false;
            minusBtn.disabled = false;
            plusBtn.classList.remove("opacity-50");
            minusBtn.classList.remove("opacity-50");
            if(totalTime > 0 && remainingTime < totalTime) {
                resetBtn.classList.remove("hidden");
            } else {
                resetBtn.classList.add("hidden");
            }
        } else {
            startBtn.classList.add("hidden");
            pauseBtn.classList.remove("hidden");
            plusBtn.disabled = true;
            minusBtn.disabled = true;
            plusBtn.classList.add("opacity-50");
            minusBtn.classList.add("opacity-50");
            resetBtn.classList.add("hidden");
        }
    }

    function fireworks() {
        const duration = 5 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 1000 };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function() {
            const timeLeft = animationEnd - Date.now();
            if (timeLeft <= 0) {
                return clearInterval(interval);
            }
            const particleCount = 50 * (timeLeft / duration);

            confetti(Object.assign({}, defaults, {
                particleCount,
                origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
            }));
            confetti(Object.assign({}, defaults, {
                particleCount,
                origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
            }));
        }, 250);
    }

    document.getElementById('plusBtn').addEventListener('click', function() {
        totalTime += 60;
        remainingTime += 60;
        updateDisplay();
        updateChart();
    });

    document.getElementById('minusBtn').addEventListener('click', function() {
        if(remainingTime >= 60) {
            totalTime = Math.max(totalTime - 60, 0);
            remainingTime = Math.max(remainingTime - 60, 0);
            updateDisplay();
            updateChart();
        }
    });

    document.getElementById('startBtn').addEventListener('click', function() {
        if(timerInterval === null && remainingTime > 0) {
            timerInterval = setInterval(function() {
                if(remainingTime > 0) {
                    remainingTime--;
                    updateDisplay();
                    updateChart();
                } else {
                    clearInterval(timerInterval);
                    timerInterval = null;
                    updateButtonState();
                    fireworks();
                }
            }, 1000);
            updateButtonState();
        }
    });

    document.getElementById('pauseBtn').addEventListener('click', function() {
        if(timerInterval !== null) {
            clearInterval(timerInterval);
            timerInterval = null;
            updateButtonState();
        }
    });

    document.getElementById('resetBtn').addEventListener('click', function() {
        remainingTime = totalTime;
        updateDisplay();
        updateChart();
        updateButtonState();
    });

    updateDisplay();
    updateChart();
    updateButtonState();
</script>

</html>
