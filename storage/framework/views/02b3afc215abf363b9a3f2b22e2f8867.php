<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .product-detail {
            margin-bottom: 20px;
        }

        .product-detail label {
            font-weight: bold;
        }

        .product-detail .value {
            font-style: italic;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Product Details</h1>

        <!-- Product Details Section -->
        <div class="product-detail">
            <label for="name">Name:</label>
            <p class="value"><?php echo e($product->name); ?></p>
        </div>

        <div class="product-detail">
            <label for="description">Description:</label>
            <p class="value"><?php echo e($product->description); ?></p>
        </div>

        <div class="product-detail">
            <label for="price">Price:</label>
            <p class="value"><?php echo e($product->price); ?></p>
        </div>

        <div class="product-detail">
            <label for="final_price">Final Price:</label>
            <p class="value"><?php echo e($product->final_price); ?></p>
        </div>

        <div class="product-detail">
            <label for="profit_margin">Profit Margin:</label>
            <p class="value"><?php echo e($product->profit_margin_type); ?> - <?php echo e($product->profit_margin_value); ?></p>
        </div>

        <!-- Tags Section -->
        <div class="product-detail">
            <label for="tags">Tags:</label>
            <?php if($product->tags->count()): ?>
                <ul>
                    <?php $__currentLoopData = $product->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productTag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="value"><?php echo e($productTag->tag); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php else: ?>
                <p class="value">No tags available.</p>
            <?php endif; ?>
        </div>

        <!-- Suppliers Section -->
        <div class="product-detail">
            <label for="suppliers">Suppliers:</label>
            <?php if($product->suppliers->count()): ?>
                <ul>
                    <?php $__currentLoopData = $product->suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="value"><?php echo e($supplier->name); ?> - <?php echo e($supplier->contact_info); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php else: ?>
                <p class="value">No suppliers available.</p>
            <?php endif; ?>
        </div>

        <!-- Back Button -->
        <a href="<?php echo e(route('products.list')); ?>" class="btn btn-custom">Back to Product List</a>
    </div>

    <!-- Bootstrap JS & jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html><?php /**PATH D:\newtask\newtask\resources\views/products/details.blade.php ENDPATH**/ ?>