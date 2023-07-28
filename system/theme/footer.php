<footer>
    Copyright Pure PHP. Designed by <a href="https://codermingle.dev/@suleymanozcan" target="_blank">Süleyman Zuckerberg</a>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $(".delete").click(function(e){
            e.preventDefault();
            var confirmation = confirm("Bu içeriği silmek istediğinizden emin misiniz?");
            if (confirmation) {
                window.location = $(this).attr('href');
            }
        });
    });
</script>