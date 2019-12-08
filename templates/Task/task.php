<div class="container-fluid">
    <div class="row">
        <!-- Ухи! Мои ухи! -->
        <div class="col-2"></div>
        <!-- Основное тело -->
        <div class="col-8">
            <table class="table table-hover">
                <thead>
                    <tr>
                    <?php 
                        foreach ($tableTaskHeader as $key => $colHeader) {
                            if ($key == 'nameUser' || $key == 'email' || $key == 'done' && $key != 'id') {
                                echo "<th scope='col'><a href='$sortPath&field=$key&lastSortField=$lastSortField&lastSort=$lastSort&currentPage=1'>$colHeader &#8597</a></th>";
                            } 
                            elseif ($key != 'id') {
                                echo "<th scope='col'>$colHeader</th>";
                            }
                        }
                    ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($tableTask as $id => $stringTask) {
                            echo "<tr>";
                            foreach ($stringTask as $key => $record) {
                                // Вывод записей "Задача" с редактированием у админа
                                if ($access && $key == 'task') {
                                    echo "<td>".$stringTask[$key]."<br><a href='$editTaskPath&id=".$stringTask['id']."'>Редактировать</a></td>";
                                } 
                                // Вывод записей "Статус" с редактированием у админа
                                elseif ($access && $key == 'done') {
                                        if ($stringTask[$key] == '00') {
                                            $valueDone = 
                                            "<form method='POST' action='?t=task&a=Edit&id=".$stringTask['id']."'>
                                            <input type='checkbox' name='done' value='1'>
                                            <input type='submit' value='Отметить выполненым'> 
                                            </form>";
                                        } elseif ($stringTask[$key] == '01') {
                                            $valueDone = 
                                            "<form method='POST' action='?t=task&a=Edit&id=".$stringTask['id']."'>
                                            <input type='checkbox' name='done' value='1'>
                                            <input type='submit' value='Отметить выполненым'> 
                                            </form><p>Отредактировано администратором</p>";
                                        } elseif ($stringTask[$key] == '10') {
                                            $valueDone = 
                                            "<p>Выполнено</p>";
                                        } elseif ($stringTask[$key] == '11') {
                                            $valueDone = 
                                            "<p>Выполнено</p><p>Отредактировано администратором</p>";
                                        }
                                        echo "<td>".$valueDone."</td>";
                                } 
                                // Вывод полей для иных пользователей
                                else {
                                    if ($key == 'done') {
                                        if ($stringTask[$key] == '00') {
                                            echo "<td></td>";
                                        } elseif($stringTask[$key] == '01') {
                                            echo "<td><p>Отредактировано администратором</p></td>";
                                        } elseif($stringTask[$key] == '10') {
                                            echo "<td><p>Выполнено</p></td>";
                                        } elseif($stringTask[$key] == '11') {
                                            echo "<td><p>Выполнено</p><p>Отредактировано администратором</p></td>";
                                        }
                                    } elseif ($key != 'id') {
                                        echo "<td>".$stringTask[$key]."</td>";
                                    }
                                }
                            }
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
            <!-- Пагинация -->
            <div class="container-fluid">
                <div class="row d-flex justify-content-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php 
                            for ($i=1; $i <=$countPage; $i++) {
                                echo "<li class='page-item'><a class='page-link' href='$paginathionPath&currentPage=$i'>$i</a></li>";
                            }
                        ?>
                    </ul>
                </nav>
                </div>
            </div>
            <!-- Добавление задач -->
            <div class="container-fluid">
                <div class="row d-flex justify-content-center">
                    <div>Добавить новую задачу</div>
                </div>
                <div class="row d-flex justify-content-center">
                <form method="POST" action="<?=$formPath?>">
                    <div class="row">
                        <div class="col-3">
                            <input type="text" class="form-control" name="nameUser" placeholder="Имя">
                        </div>
                        <div class="col-3">
                            <input type="text" class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" name="task" placeholder="Задача">
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mt-3">
                        <input type="submit" class="btn btn-primary" value="Добавить задачу!">
                    </div>
                </form>
                </div>
                <!-- Блок для вывода сообщения о успехе добавления -->
                <?php 
                if(isset($_SESSION['addTask'])){
                    echo "<div class='bg-success text-white text-center'>Данные успешно добавлены!</div>";
                    unset($_SESSION['addTask']);
                }
                ?>
            </div>
        </div>
        <!-- Ухи! Мои ухи! -->
        <div class="col-2"></div>
    </div>
</div>