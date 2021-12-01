<footer class="bg-light text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2020 Copyright:
        <a class="text-dark" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
</footer>
<script>
    function openNav() {
        document.getElementById("mySidebar").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }
    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("main").style.marginLeft= "0";
    }
    afterDetail("deneme",function () {
        const myModalEl = document.getElementById('exampleModal')
        const modal = new mdb.Modal(myModalEl)
        modal.show()
    });
    afterDetail("deneme#second",function () {
        const myModalEl2 = document.getElementById('exampleModal2')
        const modal2 = new mdb.Modal(myModalEl2)
        modal2.show()
    });
</script>
<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.2.0/mdb.min.js"
></script>
