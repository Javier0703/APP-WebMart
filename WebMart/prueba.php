<form action="pagina2.php" method="post">
    <ul>
        <li class="todos">Todos</li>
        <li class="primer">Ola</li>
        <li class="">Ola</li>
    </ul>
    <input id="hidenClass" type="hidden" name="categoria" value="">
    <button>Ola</button>
</form>

<script>
    let lis=document.querySelectorAll("ul>li")
    lis.forEach(li =>{
        li.addEventListener("click",function (e){
            let clase = li.classList[0]
            document.getElementById("hidenClass").value=clase;
        });
    });
</script>