<script>
    new DataTable('#example', {
        ajax: "<?= BASE_URL ?>/eco-facility/datatable",
        processing: true,
        serverSide: true
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-button')) {
            const facilityId = event.target.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this eco facility?')) {
                fetch(`<?= BASE_URL ?>/eco-facility/delete/${facilityId}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json().then(data => {
                        if (data.success) {
                            location.reload(); // Reload the page to see the updated list
                        } else {
                            alert(data.error || 'Error deleting eco facility.');
                        }
                    }))
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the eco facility.');
                    });
            }
        }


        if (event.target.classList.contains('visit-button')) {
            const facilityId = event.target.getAttribute('data-id');
            if (confirm('Are you sure you mark as visited this eco facility?')) {
                fetch(`<?= BASE_URL ?>/eco-facility/visit/${facilityId}`, {
                        method: 'POST'
                    })
                    .then(response => response.json().then(data => {
                        console.log(data)
                        if (data.success) {
                            location.reload(); // Reload the page to see the updated list
                        } else {
                            alert(data.error || 'Error marking eco facility.');
                        }
                    }))
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while marking the eco facility.');
                    });
            }
        }
    });
</script>