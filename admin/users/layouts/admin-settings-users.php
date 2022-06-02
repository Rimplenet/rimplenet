<style>
    .plugin-card {
        width: 97%;
        display: flex;
        margin-top: 20px;
    }

    .form-container {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    form{width: 100%;}
    .control {
        display: flex;
        flex-direction: column;
        width: 70%;     
        margin: 0 auto;
        margin-top: 8px;
    }
    label {
        margin: 5px 0;
        font-weight: 600;
        color: #787878;
    }
    .form-input{padding: 4px !important;
        border: 2px solid #e1e1e1 !important;
    }
    .rt{
        background: #f8f8f8;
    }
    .form-container img{
        width: 100%;
    }
</style>

<?php
$dir = plugin_dir_url(dirname(__FILE__));
?>

<div class="plugin-card">
    <div class="form-container">
        <img src="<?= $dir ?>img/register.png" alt="">
    </div>
    <div class="form-container rt">
        <form action="" method="POST">
            <div class="control">
                <label for="">First Name</label>
                <input type="text" name="first_name" class="form-input" placeholder="First Name">
            </div>
            <div class="control">
                <label for="">Last Name</label>
                <input type="text" name="last_name" class="form-input" placeholder="Last Name">
            </div>
            <div class="control">
                <label for="">Email</label>
                <input type="email" name="email" class="form-input" placeholder="Email">
            </div>
            <div class="control">
                <label for="">Password</label>
                <input type="text" name="password" class="form-input" placeholder="****">
            </div>
            <div class="control">
                <label for="">Confirm Passsword</label>
                <input type="text" name="confirm_password" class="form-input" placeholder="****">
            </div>
            <div class="control">
                <button class="button button-primary">Register</button>
            </div>
        </form>
    </div>
</div>