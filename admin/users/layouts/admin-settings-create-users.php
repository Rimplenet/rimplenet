
<?php

$dir = plugin_dir_url(dirname(__FILE__));
require plugin_dir_path(dirname(__FILE__)) . '/assets/php/create.php';
?>

<div class="user-card">
    <div class="form-container">
        <img src="<?= $dir ?>/assets/img/register.png" alt="">
    </div>
    <input type="hidden" id="pluginUrl" value="<?= $dir ?>">
    <div class="form-container rt">
        <form action="" method="POST" id="form-container">
            <div class="control">
                <label for="fname">First Name
                <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="First Name (Your birth name)"></span>
                </label>
                <input type="text" name="fname" id="fname" class="form-input" placeholder="First Name">
            </div>
            <div class="control">
                <label for="lname">Last Name
                <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Last Name (Your Family name)"></span>
                </label>
                <input type="text" name="lname" id="lname" class="form-input" placeholder="Last Name">
            </div>
            <div class="control">
                <label for="lname">Username
                <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Username (Choose a preferred name u would like)"></span>
                </label>
                <input type="text" name="uname" id="uname" class="form-input" placeholder="Username">
            </div>
            <div class="control">
                <label for="email">Email
                <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Email Address, Please enter a valid email address"></span>
                </label>
                <input type="email" name="email" id="email" class="form-input" placeholder="Email">
            </div>
            <div class="control">
                <label for="pwd">Password
                <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Password(Choose a strong password to secure your account)"></span>
                </label>
                <input type="text" name="password" id="pwd" class="form-input" placeholder="****">
            </div>
            <div class="control">
                <label for="pwd2">Confirm Passsword
                <span class="dashicons dashicons-editor-help rimplenet-admin-tooltip" title="Confirm Password (Confirm that the password you entered above is correct)"></span>
                </label>
                <input type="text" name="confirm_password" id="pwd2" class="form-input" placeholder="****">
            </div>
            <div class="control">
                <button type="submit" class="button button-primary">Register</button>
            </div>
        </form>
    </div>
</div>

<script>
</script>