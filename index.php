<?php

$units = "";
$bill = 0;
$error = "";
$calculated = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $units = $_POST["units"];

    if ($units === "" || !is_numeric($units)) {
        $error = "Please enter a valid number of units.";
    } elseif ($units < 0) {
        $error = "Units cannot be negative.";
    } else {

        $units = (float)$units;

        if ($units <= 50) {
            $bill = $units * 3.50;

        } elseif ($units <= 150) {
            $bill = (50 * 3.50) +
                    (($units - 50) * 4.00);

        } elseif ($units <= 250) {
            $bill = (50 * 3.50) +
                    (100 * 4.00) +
                    (($units - 150) * 5.20);

        } else {
            $bill = (50 * 3.50) +
                    (100 * 4.00) +
                    (100 * 5.20) +
                    (($units - 250) * 6.50);
        }

        $calculated = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Electricity Bill Calculator</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        * {
            font-family: 'DM Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, #dbeafe 0%, transparent 35%),
                radial-gradient(circle at bottom right, #e0e7ff 0%, transparent 35%),
                #f8fafc;
        }

        .main-card {
            max-width: 520px;
            margin: auto;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 28px;
            box-shadow: 0 25px 60px rgba(30, 41, 59, 0.12);
            overflow: hidden;
        }

        .header-section {
            background: linear-gradient(135deg, #312e81, #4f46e5);
            color: white;
            padding: 35px 30px;
            text-align: center;
        }

        .electric-icon {
            width: 65px;
            height: 65px;
            margin: 0 auto 15px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        .header-section h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header-section p {
            margin: 0;
            opacity: 0.85;
            font-size: 14px;
        }

        .form-section {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 9px;
        }

        .form-control {
            height: 52px;
            border-radius: 14px;
            border: 1px solid #cbd5e1;
            padding: 0 16px;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
        }

        .calculate-btn {
            height: 52px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            font-weight: 600;
            font-size: 16px;
            transition: 0.2s ease;
        }

        .calculate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.25);
        }

        .result-box {
            margin-top: 25px;
            padding: 22px;
            border-radius: 18px;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
        }

        .result-title {
            color: #3730a3;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .result-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            color: #475569;
        }

        .bill-amount {
            font-size: 25px;
            font-weight: 700;
            color: #312e81;
        }

        .error-box {
            margin-top: 20px;
            padding: 14px 16px;
            border-radius: 14px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: 14px;
        }

        .student-details {
            position: fixed;
            bottom: 18px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 16px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.08);
            font-size: 12px;
            color: #64748b;
        }

        .student-details strong {
            color: #334155;
        }

        @media (max-width: 576px) {

            .main-card {
                margin: 20px;
            }

            .header-section {
                padding: 28px 20px;
            }

            .form-section {
                padding: 24px 20px;
            }

            .student-details {
                position: static;
                width: fit-content;
                margin: 15px auto;
            }
        }

    </style>

</head>

<body>

    <div class="container py-5">

        <div class="main-card">

            <!-- Header -->
            <div class="header-section">

                <div class="electric-icon">
                    ⚡
                </div>

                <h1>Electricity Bill</h1>

                <p>Smart & simple bill calculation</p>

            </div>


            <!-- Form -->
            <div class="form-section">

                <form method="POST">

                    <div class="mb-4">

                        <label for="units" class="form-label">
                            Electricity Consumption
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                class="form-control"
                                id="units"
                                name="units"
                                placeholder="Enter units consumed"
                                min="0"
                                step="1"
                                value="<?php echo htmlspecialchars($units); ?>"
                            >

                            <span class="input-group-text">
                                Units
                            </span>

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary calculate-btn w-100"
                    >
                        Calculate My Bill
                    </button>

                </form>


                <!-- Error Message -->

                <?php if ($error != ""): ?>

                    <div class="error-box">
                        ⚠️ <?php echo $error; ?>
                    </div>

                <?php endif; ?>


                <!-- Result -->

                <?php if ($calculated): ?>

                    <div class="result-box">

                        <div class="result-title">
                            ⚡ Bill Summary
                        </div>

                        <div class="result-row">

                            <span>Total Consumption</span>

                            <strong>
                                <?php echo $units; ?> units
                            </strong>

                        </div>

                        <div class="result-row">

                            <span>Total Electricity Bill</span>

                            <span class="bill-amount">
                                ₹<?php echo number_format($bill, 2); ?>
                            </span>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>


    <!-- Student Details -->

    <div class="student-details">

        <strong>PRN:</strong> 12414009
        &nbsp; | &nbsp;
        <strong>Name:</strong> Misbah Shaikh

    </div>


    <!-- Bootstrap JS -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>

</html>