function selectOption(event, option) {
    event.preventDefault();

    document.querySelectorAll('.option').forEach(el => el.classList.remove('selected'));

    event.currentTarget.classList.add('selected');

    document.getElementById('finalize-button').classList.add('selected');
}

