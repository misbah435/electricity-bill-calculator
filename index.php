
<?php
$units = '';
$error = '';
$breakdown = [];
$total = 0;

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawUnits = trim($_POST['units'] ?? '');

    if ($rawUnits === '' || !is_numeric($rawUnits)) {
        $error = 'Please enter a valid number of electricity units.';
    } else {
        $units = (float)$rawUnits;

        if (!is_finite($units) || $units < 0) {
            $error = 'Units cannot be negative or invalid.';
        } else {
            $slabs = [
                ['label' => 'First 50 units', 'limit' => 50, 'rate' => 3.50],
                ['label' => 'Next 100 units', 'limit' => 100, 'rate' => 4.00],
                ['label' => 'Next 100 units', 'limit' => 100, 'rate' => 5.20],
                ['label' => 'Above 250 units', 'limit' => INF, 'rate' => 6.50],
            ];

            $remaining = $units;

            foreach ($slabs as $slab) {
                if ($remaining <= 0) {
                    break;
                }

                $consumed = min($remaining, $slab['limit']);
                $amount = $consumed * $slab['rate'];

                $breakdown[] = [
                    'label' => $slab['label'],
                    'units' => $consumed,
                    'rate' => $slab['rate'],
                    'amount' => $amount
                ];

                $total += $amount;
                $remaining -= $consumed;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Bill Calculator</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background: #f3f4fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .calculator {
            width: 100%;
            max-width: 650px;
            background: white;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 12px 40px rgba(30, 40, 100, .08);
        }

        .heading {
            color: #3730a3;
            font-weight: 800;
        }

        .form-control {
            padding: 13px;
            border-radius: 10px;
        }

        .btn-calculate {
            background: #4338ca;
            color: white;
            border: none;
            padding: 13px;
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-calculate:hover {
            background: #312e81;
            color: white;
        }

        .result {
            background: #eef2ff;
            border-radius: 14px;
            padding: 22px;
            margin-top: 25px;
        }

        .total {
            font-size: 2rem;
            color: #3730a3;
            font-weight: 800;
        }

        .table th {
            color: #4338ca;
        }
    </style>
</head>

<body>
<div class="calculator">

    <div class="text-center mb-4">
        <h2 class="heading">Electricity Bill Calculator</h2>
        <p class="text-muted">Calculate your electricity charges based on units consumed.</p>
    </div>

    <form method="POST" action="">
        <label for="units" class="form-label fw-semibold">
            Electricity Units Consumed
        </label>

        <input
            type="number"
            name="units"
            id="units"
            class="form-control mb-3"
            placeholder="Enter units consumed"
            min="0"
            step="any"
            value="<?= e($units) ?>"
            required
        >

        <button type="submit" class="btn btn-calculate w-100">
            Calculate Bill
        </button>
    </form>

    <?php if ($error): ?>
        <div class="alert alert-danger mt-3">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error): ?>
        <div class="result">
            <h5 class="fw-bold mb-3">Bill Summary</h5>

            <p class="mb-1">
                Units consumed: <strong><?= e($units) ?></strong>
            </p>

            <div class="table-responsive mt-3">
                <table class="table table-bordered bg-white">
                    <thead>
                    <tr>
                        <th>Slab</th>
                        <th>Units</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($breakdown as $item): ?>
                        <tr>
                            <td><?= e($item['label']) ?></td>
                            <td><?= number_format($item['units'], 2) ?></td>
                            <td>₹<?= number_format($item['rate'], 2) ?></td>
                            <td>₹<?= number_format($item['amount'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-3">
                <p class="mb-1 text-muted">Total Electricity Bill</p>
                <div class="total">₹<?= number_format($total, 2) ?></div>
            </div>
        </div>
    <?php endif; ?>

    <hr class="mt-4">

    <div class="text-center text-muted small">
        <p class="mb-1">Developed by <strong>Misbah Shaikh</strong></p>
        <p class="mb-1">PRN: 12414009</p>
        <p class="mb-0">Taxes and additional charges are not included.</p>
    </div>

</div>
</body>
</html>
