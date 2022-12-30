<?php

require plugin_dir_path(dirname(__FILE__)) . '/assets/php/get.php';
$no_image = "https://as1.ftcdn.net/v2/jpg/02/33/46/24/1000_F_233462402_Fx1yke4ng4GA8TJikJZoiATrkncvW6Ib.jpg";
?>
<!-- <h2> Active Users</h2> -->
<script src="https://cdn.tailwindcss.com"></script>
<div class="flex flex-col px-5 m-5 bg-white">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="border-b">
                        <tr>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                S/N
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                <?= ucfirst(str_replace('_', ' ', "user_profile_photo_url")) ?>
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                <?= ucfirst(str_replace('_', ' ', "gender")) ?>
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                <?= ucfirst(str_replace('_', ' ', "country_of_origin")) ?>
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                <?= ucfirst(str_replace('_', ' ', "date_of_birth")) ?>
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                <?= ucfirst(str_replace('_', ' ', "identity_document_date_of_issue")) ?>
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                <?= ucfirst(str_replace('_', ' ', "identity_document_date_of_expiry")) ?>
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                <?= ucfirst(str_replace('_', ' ', "identity_document_file_url_front")) ?>
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                <?= ucfirst(str_replace('_', ' ', "identity_document_file_url_back")) ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $k => $user): ?>
                                <tr class="border-b">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= $k + 1 ?>
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <a target="_blank" href="<?= $user['user_profile_photo_url'] ?? $no_image ?>" data-toggle="lightbox">
                                            <img height="100px" width="100px" src="<?= $user['user_profile_photo_url'] ?? $no_image ?>" alt="">
                                        </a>
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <?= $user['gender'] ?? "__" ?>
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <?= $user['country_of_origin'] ?? '__' ?>
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <?= $user['date_of_birth'] ?? '__' ?>
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <?= $user['identity_document_date_of_issue'] ?? '__' ?>
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <?= $user['identity_document_date_of_expiry'] ?? '__' ?>
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <a target="_blank" href="<?= $user['identity_document_file_url_front'] ?? $no_image ?>" data-toggle="lightbox">
                                            <img height="100px" width="100px" src="<?= $user['identity_document_file_url_front'] ?? $no_image ?>" alt="">
                                        </a>
                                    </td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                        <a target="_blank" href="<?= $user['identity_document_file_url_back'] ?? $no_image ?>" data-toggle="lightbox">
                                            <img height="100px" width="100px" src="<?= $user['identity_document_file_url_back'] ?? $no_image ?>" alt="">
                                        </a>
                                    </td>
                                </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
</script>