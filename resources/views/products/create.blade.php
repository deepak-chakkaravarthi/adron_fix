<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .form-group {
            margin-bottom: 1.5rem;
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
        <h1 class="text-center mb-4">Create Product</h1>
        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <!-- Product Name -->
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <!-- Product Description -->
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
            </div>

            <!-- Product Price -->
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>

            <!-- Product Image -->
            <div class="form-group">
                <label for="image">Image (URL):</label>
                <input type="text" name="image" id="image" class="form-control" onchange="validateImage()">
                <span id="image-error" class="text-danger" style="display: none;">The image field must be a valid
                    URL.</span>
            </div>

            <div class="form-group">
                <label>Categories:</label><br>
                <div id="category-group">
                    @foreach ($categories as $category)
                        <div class="form-check">
                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                class="form-check-input category-checkbox">
                            <label class="form-check-label">{{ $category->name }}</label>
                        </div>
                    @endforeach
                </div>
                <span id="category-error" class="text-danger" style="display: none;">Please select at least one
                    category.</span>
            </div>


            <!-- Tags -->
            <div class="form-group" id="tags">
                <label for="tags">Tags:</label>
                <div class="tag-input mb-2">
                    <input type="text" name="tag[]" placeholder="Tag Name" class="form-control mb-2 tag-field" required>
                    <button type="button" class="btn btn-danger btn-sm remove-tag"
                        onclick="removeTag(this)">Remove</button>
                </div>
                <button type="button" class="btn btn-info btn-sm mt-2" onclick="addTag()">Add More Tags</button>
                <span id="tag-error" class="text-danger" style="display: none;">Tags must be unique.</span>
            </div>

            <!-- Suppliers -->
            <div class="form-group" id="suppliers">
                <label>Suppliers:</label>
                <div class="supplier-input mb-2">
                    <input type="text" name="supplier_names[]" placeholder="Supplier Name" class="form-control mb-2"
                        required>
                    <input type="text" name="supplier_contacts[]" placeholder="Supplier Contact Info"
                        class="form-control mb-2" required>
                    <button type="button" class="btn btn-danger btn-sm remove-supplier"
                        onclick="removeSupplier(this)">Remove</button>
                </div>
                <button type="button" class="btn btn-info btn-sm mt-2" onclick="addSupplier()">Add Supplier</button>
            </div>
            <!-- Profit Margin -->
            <div class="form-group">
                <label for="profit_margin_type">Profit Margin Type:</label>
                <select name="profit_margin_type" id="profit_margin_type" class="form-control"
                    onchange="calculatePrice()">
                    <option value="percentage">Percentage</option>
                    <option value="amount">Amount</option>
                </select>
            </div>

            <div class="form-group">
                <label for="profit_margin_value">Profit Margin Value:</label>
                <input type="number" name="profit_margin_value" id="profit_margin_value" class="form-control"
                    onchange="calculatePrice()" required>
            </div>

            <!-- Final Price (Read-Only) -->
            <div class="form-group">
                <label for="final_price">Final Price:</label>
                <input type="text" name="final_price" id="final_price" class="form-control" readonly>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-custom">Create Product</button>
        </form>
    </div>

    <!-- Bootstrap JS & jQuery (for Bootstrap JS components like tooltips, modals) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function addSupplier() {
            const container = document.getElementById('suppliers');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'supplier_ids[]';
            input.placeholder = 'Supplier';
            input.classList.add('form-control', 'mb-2');
            container.appendChild(input);
        }

        // Calculate final price (example logic)
        function calculatePrice() {
            const price = parseFloat(document.getElementById('price').value);
            const profitMarginValue = parseFloat(document.getElementById('profit_margin_value').value);
            const profitMarginType = document.getElementById('profit_margin_type').value;

            let finalPrice = price;

            if (profitMarginType === 'percentage') {
                finalPrice += (finalPrice * profitMarginValue) / 100;
            } else if (profitMarginType === 'amount') {
                finalPrice += profitMarginValue;
            }

            document.getElementById('final_price').value = finalPrice.toFixed(2);
        }

        function addSupplier() {
            const container = document.getElementById('suppliers');
            const div = document.createElement('div');
            div.classList.add('supplier-input', 'mb-2');
            div.innerHTML = `
            <input type="text" name="supplier_names[]" placeholder="Supplier Name" class="form-control mb-2" required>
            <input type="text" name="supplier_contacts[]" placeholder="Supplier Contact Info" class="form-control mb-2" required>
            <button type="button" class="btn btn-danger btn-sm remove-supplier" onclick="removeSupplier(this)">Remove</button>
        `;
            container.insertBefore(div, container.querySelector('.btn-info'));
        }

        // Remove supplier input fields
        function removeSupplier(button) {
            button.parentElement.remove();
        }


        // Add more tag fields dynamically
        function addTag() {
            const container = document.getElementById('tags');
            const div = document.createElement('div');
            div.classList.add('tag-input', 'mb-2');
            div.innerHTML = `
        <input type="text" name="tag[]" placeholder="Tag Name" class="form-control mb-2 tag-field" required>
        <button type="button" class="btn btn-danger btn-sm remove-tag" onclick="removeTag(this)">Remove</button>
    `;
            container.insertBefore(div, container.querySelector('.btn-info'));
        }

        // Remove tag input fields
        function removeTag(button) {
            button.parentElement.remove();
            validateTags(); // Revalidate tags after removal
        }

        // Validate unique tags
        function validateTags() {
            const tagFields = document.querySelectorAll('.tag-field');
            const tagValues = Array.from(tagFields).map(field => field.value.trim().toLowerCase());
            const hasDuplicates = tagValues.some((value, index) => tagValues.indexOf(value) !== index);

            const tagError = document.getElementById('tag-error');
            if (hasDuplicates) {
                tagError.style.display = 'block';
            } else {
                tagError.style.display = 'none';
            }
        }

        // Validate at least one category selected
        function validateCategories() {
            const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
            const isAnyCategoryChecked = Array.from(categoryCheckboxes).some(checkbox => checkbox.checked);

            const categoryError = document.getElementById('category-error');
            if (!isAnyCategoryChecked) {
                categoryError.style.display = 'block'; // Show error if none selected
            } else {
                categoryError.style.display = 'none'; // Hide error if at least one selected
            }
        }

        // Event listeners for validation
        document.getElementById('tags').addEventListener('input', validateTags);
        document.getElementById('category-group').addEventListener('change', validateCategories);

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function (e) {
            validateTags();
            validateCategories();

            const tagError = document.getElementById('tag-error').style.display;
            const categoryError = document.getElementById('category-error').style.display;

            if (tagError === 'block' || categoryError === 'block') {
                e.preventDefault(); // Prevent form submission if validation fails
            }
        });


        function validateImage() {
            const image = document.getElementById('image').value;
            const imageError = document.getElementById('image-error');

            // Regular expression for URL validation
            const urlPattern = /^(https?:\/\/[^\s$.?#].[^\s]*)$/;

            // Validate if the image is a valid URL
            if (image && !urlPattern.test(image)) {
                imageError.style.display = 'block';  // Show the error
            } else {
                imageError.style.display = 'none';  // Hide the error if valid
            }
        }


    </script>
</body>

</html>