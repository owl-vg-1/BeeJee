<div class="container-fluid">

<div class="d-flex justify-content-center vh-100 align-items-center">
    
<form  class="w-50" action="<?=$editPath?>" method="post">
<h2 class="text-center">Отредактировать текст задачи:</h2>
<p>Задача:</p>
<input class="m-2 w-100" type="text" name="task" value="<?=$editTask['task']?>"><br>
<input class="m-2 w-100" type="submit" value="Изменить">
</form>

</div>
</div>