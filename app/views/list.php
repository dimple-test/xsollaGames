<table class="table table-striped" id="gameListTable" >
    <thead>
        <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">Platform</th>
        <th scope="col">Star Rating</th>
        <th scope="col">Review Text</th>
        <th scope="col">Last Played</th>
        <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data)) {
                foreach($data as $k => $val) {
                    // echo "<pre>";
                    // print_r($val);
                    ?>
                    <tr>
                        <th scope="row"><?php echo $val['id']; ?></th>
                        <td><?php echo $val['title']; ?></td>
                        <td><?php echo $val['platform']; ?></td>
                        <td><?php echo $val['star_rating']; ?></td>
                        <td><?php echo $val['review']; ?></td>
                        <td><?php echo date('m/d/Y h:i', strtotime($val['last_played'])); ?></td>
                        <td>
                            <a class="editGame" data-id="<?php echo $val['id']; ?>" href="#" data-bs-toggle="modal" data-bs-target="#addEditGameModal"><i class="bi bi-pencil-square me-3"></i></a>
                            <a href="#" data-id="<?php echo $val['id']; ?>" class="deleteGame"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php
                }   
        ?>
        <?php } else { ?> <tr>  <td>No games found</td> </tr><?php }?>
        
    </tbody>
</table>
