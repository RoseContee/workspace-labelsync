$(() => {
    $('[name="timezoneOffset"]').val(new Date().getTimezoneOffset());
});

const dateFormat = (num) => {
    const date = new Date(num);
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
}

const pad = (num) => {
    return `0${num}`.slice(-2);
}
