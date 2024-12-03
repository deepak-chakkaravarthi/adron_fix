<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 40px;
            font-size: 2.5rem;
            color: #333;
        }

        .container {
            width: 60%;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }

        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        textarea {
            height: 150px;
            resize: vertical;
        }

        .checkbox-group label {
            display: inline-block;
            margin-right: 15px;
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            background-color: #f8d7da;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .supplier-item, .tag-item {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .supplier-item span {
            flex: 1;
        }

        .remove-btn {
            background-color: #e74c3c;
            border: none;
            color: white;
            padding: 5px 10px;
            font-size: 0.9rem;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .remove-btn:hover {
            background-color: #c0392b;
        }

        .add-btn {
            background-color: #3498db;
            color: white;
            padding: 8px 15px;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 5px;
        }

        .add-btn:hover {
            background-color: #2980b9;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-link {
            font-size: 0.9rem;
            color: #007BFF;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Update Product</h1>

    <!-- Display error messages -->
    <div id="error-message" class="error-message" style="display:none;">
        <p>Please select at least one category.</p>
    </div>

    <div class="container">
        <form id="product-form" action="<?php echo e(route('products.update', $product->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" value="<?php echo e($product->name); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Product Description:</label>
                <textarea id="description" name="description" required><?php echo e($product->description); ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="<?php echo e($product->price); ?>" required>
            </div>

            <div class="form-group">
                <label>Categories:</label>
                <div class="checkbox-group">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label>
                            <input type="checkbox" name="category_ids[]" value="<?php echo e($category->id); ?>"
                                   <?php echo e($product->categories->contains($category->id) ? 'checked' : ''); ?>>
                            <?php echo e($category->name); ?>

                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="form-group">
                <label>Suppliers:</label><br>
                <div id="suppliers-container">
                    <?php $__currentLoopData = $product->suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="supplier-item" id="supplier-<?php echo e($supplier->id); ?>">
                            <input type="hidden" name="existing_supplier_ids[]" value="<?php echo e($supplier->id); ?>">
                            <span><?php echo e($supplier->name); ?> - <?php echo e($supplier->contact_info); ?></span>
                            <button type="button" class="remove-btn remove-supplier-btn" data-supplier-id="<?php echo e($supplier->id); ?>">Remove</button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div id="new-suppliers-container"></div>
                <button type="button" id="add-supplier-btn" class="add-btn">Add New Supplier</button>
            </div>

            <div class="form-group">
                <label>Tags:</label>
                <div id="tags-container">
                    <?php $__currentLoopData = $product->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="tag-item">
                            <input type="text" name="tag[]" value="<?php echo e($tag->tag); ?>" class="form-control">
                            <button type="button" class="remove-btn remove-tag-btn">Remove</button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <button type="button" id="add-tag-btn" class="add-btn">Add Tag</button>
            </div>

            <div class="form-group">
                <label for="profit_margin_type">Profit Margin Type:</label>
                <select id="profit_margin_type" name="profit_margin_type">
                    <option value="percentage" <?php echo e($product->profit_margin_type === 'percentage' ? 'selected' : ''); ?>>Percentage</option>
                    <option value="amount" <?php echo e($product->profit_margin_type === 'amount' ? 'selected' : ''); ?>>Amount</option>
                </select>
            </div>

            <div class="form-group">
                <label for="profit_margin_value">Profit Margin Value:</label>
                <input type="number" id="profit_margin_value" name="profit_margin_value" value="<?php echo e($product->profit_margin_value); ?>" required>
            </div>

            <div class="form-footer">
                <button type="submit">Update Product</button>
                <a href="<?php echo e(route('products.list')); ?>" class="back-link">Back to Product List</a>
            </div>
        </form>
    </div>

    <script>
    // Validation logic for categories
    document.getElementById('product-form').addEventListener('submit', function(event) {
        const selectedCategories = document.querySelectorAll('input[name="category_ids[]"]:checked');
        const errorMessage = document.getElementById('error-message');

        if (selectedCategories.length === 0) {
            event.preventDefault(); // Prevent form submission
            errorMessage.style.display = 'block'; // Show error message
        }
    });

    // Existing JavaScript for handling adding/removing suppliers
    document.getElementById('add-supplier-btn').addEventListener('click', function () {
        const suppliersContainer = document.getElementById('new-suppliers-container');
        const newSupplierDiv = document.createElement('div');
        newSupplierDiv.classList.add('supplier-item');
        newSupplierDiv.innerHTML = `
            <input type="text" name="new_suppliers[]" class="form-control" placeholder="Enter new supplier name" required>
            <input type="text" name="new_supplier_contact[]" class="form-control" placeholder="Enter contact info" required>
            <button type="button" class="remove-btn">Remove</button>
        `;
        suppliersContainer.appendChild(newSupplierDiv);

        newSupplierDiv.querySelector('.remove-btn').addEventListener('click', function () {
            suppliersContainer.removeChild(newSupplierDiv);
        });
    });

    // Handling "Add Tag" functionality
    document.getElementById('add-tag-btn').addEventListener('click', function () {
        const tagsContainer = document.getElementById('tags-container');
        const newTagDiv = document.createElement('div');
        newTagDiv.classList.add('tag-item');
        newTagDiv.innerHTML = `
            <input type="text" name="tag[]" class="form-control" placeholder="Enter tag" required>
            <button type="button" class="remove-btn remove-tag-btn">Remove</button>
        `;
        tagsContainer.appendChild(newTagDiv);

        // Add event listener to the new "Remove" button for tags
        newTagDiv.querySelector('.remove-btn').addEventListener('click', function () {
            tagsContainer.removeChild(newTagDiv);
        });
    });

    // Removing tags
    document.querySelectorAll('.remove-tag-btn').forEach(button => {
        button.addEventListener('click', function () {
            this.closest('.tag-item').remove();
        });
    });

    // Removing suppliers
    document.querySelectorAll('.remove-supplier-btn').forEach(button => {
        button.addEventListener('click', function () {
            const supplierId = this.getAttribute('data-supplier-id');
            document.getElementById('supplier-' + supplierId).remove();
        });
    });
</script>


</body>
</html>
<?php /**PATH D:\newtask\newtask\resources\views/products/edit.blade.php ENDPATH**/ ?>