document.addEventListener("DOMContentLoaded", () => {
  const $userName = document.querySelector("#userName");
  const $email = document.querySelector("#email");
  const $password = document.querySelector("#password");
  const $rptemail = document.querySelector("#rpt-email");
  const $rptpassword = document.querySelector("#rpt-password");
  const $emailError = document.querySelector(".email-error");
  const $userNameError = document.querySelector(".userName-error");
  const $passwordError = document.querySelector(".password-error");
  const $rptpasswordError = document.querySelector(".password-not-match");
  const $rptemailerror = document.querySelector(".email-not-match");
  const $checkTickUserName = document.querySelector(".userNames");
  const $checkTickRptEmail = document.querySelector(".rptEmails");
  const $checkTickPassword = document.querySelector(".Checkpassword");
  const $rptcheckpassword = document.querySelector(".rptcheckpassword");
  const $checkEmails = document.querySelector(".checkEmails");



  const $submit = document.querySelector("#button");
  let userNameIsValid = false;
  let emailIsValid = false;
  let passwordIsValid = false;
  let passwordIsSame = false;
  let emailIsSame = false;
  let emailExists = false;
  const checkIfEmailExists = (email) => {
    obj = {
      email: email
    };

    // Ajax request
    $.ajax(
     'http://hotel.collegelink.localhost/public/ajax/register_email_check.php',
      {
        type: "POST",
        dataType: "html",
        data: obj,
        async: false,
      }).done(function(result) {
          if (result === "1" ) {
            emailExists = true;
          }
          else {
            emailExists = false;
          }
      });
    };

  const getUserNameValidation = (userName) => {
    if (userName !== "" && userName.length >= 8  && /^[A-Za-z]+$/.test(userName)) {
      userNameIsValid = true;
    }
    else {
      userNameIsValid = false;
    }
  };

  const getEmailValidation = (email) => {
    checkIfEmailExists(email);
    if (email !== "" && /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email) && !emailExists) {
      emailIsValid = true;
    }
    else {
      emailIsValid = false;
    }
  };

  const getpasswordValidation = (password) => {
    if (email !== "" && password.length > 4) {
      passwordIsValid = true;
    }
    else {
      passwordIsValid = false;
    }
  };

  const checkSigninBtn = () => {
    if (userNameIsValid && emailIsValid && passwordIsValid && passwordIsSame && emailIsSame) {
      $submit.disabled = false;
      $submit.style.cursor = "pointer";
    }
    else {
      $submit.disabled = true;
      $submit.style.cursor = "no-drop";
    }
  };

  const getpasswordSimilar = (password,rptpassword) => {
    if (password === rptpassword) {
      passwordIsSame = true;
    }
    else {
      passwordIsSame = false;
    }
  };

  const getEmailSimilar = (email ,rptemail) => {
    if ( email.toLowerCase() === rptemail.toLowerCase()) {
      emailIsSame = true;
    }
    else {
      emailIsSame = false;
    }
    return emailIsSame;
  };

  $userName.addEventListener("input", (e) => {
    getUserNameValidation(e.target.value);
    let $userNameVal = e.target.value;
    if ($userNameVal == "" || $userNameVal.length < 8 || !/^[A-Za-z]+$/.test($userNameVal)) {
      $userName.classList.add("is-invalid");
      $checkTickUserName.className ="fas fa-times-circle";
      $checkTickUserName.classList.remove("not-valid");
      $userName.classList.remove("is-valid");
      $userNameError.innerHTML= ("Enter a valid Username! Minimum number of characters is 8 and only alphabet symbols are allowed.");
    }
    else {
      $checkTickUserName.className ="fas fa-check-circle";
      $checkTickUserName.classList.remove("not-valid");
      $userName.classList.add("is-valid");
      $userName.classList.remove("is-invalid");
      $userNameError.innerHTML= ("");
    }
    checkSigninBtn();
  });

  $rptemail.addEventListener("input", (e) => {
    mail = $email.value;
    getEmailSimilar(e.target.value,mail);

    if (!emailIsSame) {
     $checkTickRptEmail.className ="fas fa-times-circle";
     $checkTickRptEmail.classList.remove("not-valid");
     $rptemail.classList.add("invalid");
     $rptemail.classList.remove("is-valid");
     $rptemailerror.innerHTML= ("Emails don't match!");
    }
    else {
      $checkTickRptEmail.className ="fas fa-check-circle";
      $checkTickRptEmail.classList.remove("not-valid");
      $rptemail.classList.add("is-valid");
      $rptemail.classList.remove("is-invalid");
      $rptemailerror.innerHTML= (" ");
    }
    checkSigninBtn();
  });

  $rptpassword.addEventListener("input", (e) => {
    pass = $password.value;
    getpasswordSimilar(e.target.value,pass);
    if (!passwordIsSame) {
      $rptcheckpassword.className ="fas fa-times-circle";
      $rptcheckpassword.classList.remove("not-valid");
      $rptpassword.classList.add("is-invalid");
      $rptpassword.classList.remove("is-valid");
      $rptpasswordError.innerHTML= ("Passwords don't match!");
    }
    else {
      $rptcheckpassword.className ="fas fa-check-circle";
      $rptcheckpassword.classList.remove("not-valid");
      $rptpassword.classList.add("is-valid");
      $rptpassword.classList.remove("is-invalid");
      $rptpasswordError.innerHTML= ("");
    }
    checkSigninBtn();
  });

  $email.addEventListener("input", (e) => {
    getEmailValidation(e.target.value);
    if (emailExists) {
      $email.classList.add("is-invalid");
      $email.classList.remove("is-valid");
      $checkEmails.className ="fas fa-times-circle";
      $checkEmails.classList.remove("not-valid");
      $emailError.innerHTML= ("A user with this email already exists!");
      checkSigninBtn();
      return;
    }
    else {
      $checkEmails.className ="fas fa-check-circle";
      $checkEmails.classList.remove("not-valid");
      $email.classList.add("is-valid");
      $email.classList.remove("is-invalid");
      $emailError.innerHTML= (" ");
    }
    if (!emailIsValid) {
      $email.classList.add("is-invalid");
      $email.classList.remove("is-valid");
      $checkEmails.className ="fas fa-times-circle";
      $checkEmails.classList.remove("not-valid");
      $emailError.innerHTML= ("The given email is not a valid email address! Please insert a valid email address.");
    }
    else {
      $checkEmails.className ="fas fa-check-circle";
      $checkEmails.classList.remove("not-valid");
      $email.classList.add("is-valid");
      $email.classList.remove("is-invalid");
      $emailError.innerHTML= ("");
    }

    mail = $rptemail.value;
    getEmailSimilar(e.target.value, mail);
    if (!emailIsSame) {
      $rptemail.classList.add("is-invalid");
      $rptemail.classList.remove("is-valid");
      $checkTickRptEmail.className ="fas fa-times-circle";
      $checkTickRptEmail.classList.remove("not-valid");
      $rptemailerror.innerHTML= ("Email didnt match!!");
    }
    else {
      $checkTickRptEmail.className ="fas fa-check-circle";
      $checkTickRptEmail.classList.remove("not-valid");
      $rptemail.classList.add("is-valid");
      $rptemail.classList.remove("is-invalid");
      $rptemailerror.innerHTML= (" ");
     }
    checkSigninBtn();
  });

  $password.addEventListener("input", (e) => {
    getpasswordValidation(e.target.value);

    if (!passwordIsValid) {
      $password.classList.add("is-invalid");
      $password.classList.remove("is-valid");
      $checkTickPassword.className ="fas fa-times-circle";
      $checkTickPassword.classList.remove("not-valid");
      $passwordError.innerHTML= ("Password must have more than 4 characters!");
    }
    else {
      $password.classList.add("is-valid");
      $password.classList.remove("is-invalid");
      $checkTickPassword.className ="fas fa-check-circle";
      $checkTickPassword.classList.remove("not-valid");
      $passwordError.innerHTML= ("");
    }

    pass = $rptpassword.value;
    getpasswordSimilar(e.target.value, pass);
    if (!passwordIsSame) {
      $rptpassword.classList.add("is-invalid");
      $rptpassword.classList.remove("is-valid");
      $rptcheckpassword.className ="fas fa-times-circle";
      $rptcheckpassword.classList.remove("not-valid");
      $rptpasswordError.innerHTML= ("Passwords don't match!");
    }
    else {
      $rptcheckpassword.className ="fas fa-check-circle";
      $rptcheckpassword.classList.remove("not-valid");
      $rptpassword.classList.add("is-valid");
      $rptpassword.classList.remove("is-invalid");
      $rptpasswordError.innerHTML= ("");
    }
    checkSigninBtn();
  });

  $userNameError.innerHTML= (" ");
  $emailError.innerHTML= ("");
  $passwordError.innerHTML= (" ");
  $rptpasswordError.innerHTML= (" ");
  $rptemailerror.innerHTML= (" ");
});