<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('reload').addEventListener('click', function() {
        fetch('{{ route("captcha.reload") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            document.querySelector('.captcha-img').innerHTML = data.captcha;
        });
    });
});
</script>
