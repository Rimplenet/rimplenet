<style>
    .user-card {
        width: 97%;
        display: flex;
        margin-top: 20px;
        margin: 0 8px 16px;
        background-color: #fff;
        border: 1px solid #dcdcde;
        box-sizing: border-box;
    }

    .form-container {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    form {
        width: 100%;
    }

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

    .form-input {
        padding: 4px !important;
        border: 2px solid #e1e1e1 !important;
    }

    .rt {
        background: #f8f8f8;
    }

    .form-container img {
        width: 100%;
    }

    .error {
        border-color: #c58383 !important;
    }

    .success {
        border-color: #5ce68f !important;
    }
</style>

<?php

$dir = plugin_dir_url(dirname(__FILE__));
require plugin_dir_path(dirname(__FILE__)).'/assets/php/create.php';
?>

<div class="user-card">
    <div class="form-container">
        <img src="<?= $dir ?>/assets/img/register.png" alt="">
    </div>
    <div class="form-container rt">
        <form action="" method="POST" id="form-container">
            <div class="control">
                <label for="fname">First Name</label>
                <input type="text" name="first_name" id="fname" class="form-input" placeholder="First Name">
            </div>
            <div class="control">
                <label for="lname">Last Name</label>
                <input type="text" name="last_name" id="lname" class="form-input" placeholder="Last Name">
            </div>
            <div class="control">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-input" placeholder="Email">
            </div>
            <div class="control">
                <label for="pwd">Password</label>
                <input type="text" name="password" id="pwd" class="form-input" placeholder="****">
            </div>
            <div class="control">
                <label for="pwd2">Confirm Passsword</label>
                <input type="text" name="confirm_password" id="pwd2" class="form-input" placeholder="****">
            </div>
            <div class="control">
                <button type="submit" class="button button-primary">Register</button>
            </div>
        </form>
    </div>
</div>

<!-- <script>
    let form = document.getElementById('form-container'),
        firstName = document.getElementById('fname'),
        lastName = document.getElementById('lname'),
        email = document.getElementById('email'),
        password = document.getElementById('pwd'),
        cpassword = document.getElementById('pwd2')
    canSend = []
    pathFile = "<?= $path ?>"

    const showMssg = (input, type = 'error') => {
        type == 'error' ? input.classList.add('error') : input.className = 'form-input success'
    }
    const checkempty = (input = []) => {
        canSend = []
        input.forEach(item => {
            if (item.value == '') {
                showMssg(item)
                canSend.push(false)
            } else {
                showMssg(item, true)
                canSend.push(true)
            }
        });
    }

    var requestOptions = {
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
        },
        body: {
            first_name: firstName,
            last_name: lastName,
            user_email: email,
            user_pass: password
        },
        redirect: 'follow'
    };

    form.onsubmit = (e) => {
        e.preventDefault();

        checkempty([firstName, lastName, email, password, cpassword])

        if (!canSend.includes(false)) {
            fetch(redLocation, requestOptions).then(res => res.json())
            .then(data => {
                console.log(data)
            }).catch(err =>  console.log(err))
        }
    }
</script> -->