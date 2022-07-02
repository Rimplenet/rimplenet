let form = document.getElementById("form-container"),
  firstName = document.getElementById("fname"),
  lastName = document.getElementById("lname"),
  uname = document.getElementById("uname"),
  email = document.getElementById("email"),
  password = document.getElementById("pwd"),
  cpassword = document.getElementById("pwd2");
canSend = [];

const showMssg = (input, type = "error") => {
  type == "error"
    ? (input.className = "form-input error")
    : (input.className = "form-input success");
};
const checkempty = (input = []) => {
  canSend = [];
  input.forEach((item) => {
    if (item.value == "") {
      showMssg(item);
      canSend.push(false);
    } else {
      showMssg(item, true);
      canSend.push(true);
    }
  });
};

cpassword.oninput = (e) => {
  if (e.target.value == password.value) {
    showMssg(e.target, "success");
    showMssg(password, "success");
    canSend.push(true);
  } else {
    showMssg(e.target);
    canSend = [false];
  }
};

const validatePassword = () => {
  if (password.value.length > 6) {
    if (password.value == cpassword.value) {
      showMssg(password, "success");
      showMssg(cpassword, "success");
      canSend = [true];
    } else {
      showMssg(password);
      showMssg(cpassword);
      canSend = [false];
    }
  } else {
    showMssg(password);
    canSend = [false];
  }
};

form.onsubmit = (e) => {
  e.preventDefault();

  checkempty([firstName, lastName, email, password, uname, cpassword]);
  validatePassword();
  console.log("Whats ")

  if (!canSend.includes(false)) {
    form.submit();
  }
};
// let url = document.getElementById('pluginUrl')
// fetch(url.value+'assets/php/create.php').then(res => res.json()).then(data => {
// }).catch(err => {
//     console.log(err)
// })

// swal({
//     title: "Good job!",
//     text: "You clicked the button!",
//     icon: "success",
//   });
