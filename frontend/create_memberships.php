<?php
// Start the session
session_start();

// Validate the session
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include the database connection
require_once '../backend/db.php';

// Define the module slug
$mod_slug = 'memberships';

// Define the page title
$page_title = 'Create Membership';

// Include the header
require_once 'header.php';
?>

<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Create Membership</h3>
                <p class="mt-1 text-sm text-gray-600">Create a new membership record.</p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form id="create-membership-form">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="name" id="name" class="focus:ring-orange-500 focus:border-orange-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" placeholder="Membership name">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <div class="mt-1">
                                    <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Membership description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" name="price" id="price" class="focus:ring-orange-500 focus:border-orange-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" placeholder="Membership price">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="duration" class="block text-sm font-medium text-gray-700">Duration</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" name="duration" id="duration" class="focus:ring-orange-500 focus:border-orange-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" placeholder="Membership duration in months">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-200 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">Create Membership</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-membership-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/memberships.php',
                data: formData,
                success: function(response) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>