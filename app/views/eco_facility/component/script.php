<script>
    new DataTable('#example', {
        ajax: "<?= BASE_URL ?>/eco-facility/datatable",
        processing: true,
        serverSide: true
    });
</script>