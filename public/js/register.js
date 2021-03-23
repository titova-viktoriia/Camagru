function validate_form ()
{
    let loginReg = RegExp("^[a-z][a-z0-9]{1,20}$")
    let nameReg = RegExp("^[A-Z][a-z]{1,30}$")
    let passwordReg = RegExp("(?=.*[0-9])(?=.*[a-zA-Z])")

    if (document.registration.login.value.length > 15)
    {
        alert("Логин может включать в себя максимум 15 символов")
        return false
    }

    if (loginReg.test(document.registration.login.value) === false)
    {
        alert("Некорректный логин. Логин может включать в себя только цифры " +
            "и маленькие латинские буквы.")
        return false
    }

    if (nameReg.test(document.registration.name.value) === false)
    {
        alert("Некорректное имя.")
        return false
    }

    if (nameReg.test(document.registration.surname.value) === false)
    {
        alert("Некорректная фамилия.")
        return false
    }

    if (passwordReg.test(document.registration.password.value) === false)
    {
        alert("Некорректный пароль.")
        return false
    }

    if (document.registration.password.value.length < 4)
    {
        alert("Пароль слишком короткий")
        return false
    }

    if (document.registration.password.value !== document.registration.password2.value)
    {
        alert("Пароли не совпадают")
        return false
    }
    return true;
}
