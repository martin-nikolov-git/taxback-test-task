    <main>
        
        <div class="container">
            <form method="get">
                <div class="row form align-items-end">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="search">Author Name</label>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Author name" value="<?= $search ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="items_per_page">Items per page</label>
                            <select class="form-control" name="items_per_page" id="items_per_page">
                                <?php foreach($items_per_page_options as $num_option): ?>
                                    <option value="<?= $num_option ?>" <?=($num_option == $items_per_page)? 'selected' : ''; ?>>
                                        <?= $num_option ?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
            <div class="row content">
                <div>
                    <?php foreach($books as $book): ?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="thumbnail">
                                <div class="caption">
                                    <img src="https://via.placeholder.com/150x150" alt="Placeholder image">
                                    <h4><?= $book->name ?></h4>
                                    <span class="author-name">- <?= $book->author ?></span>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris et leo sed odio imperdiet scelerisque sit amet ac diam. Nam ornare nec ipsum tincidunt aliquet.
                                    </p>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="created-at col-sm-6 col-xs-12">
                                                Created at:
                                                <span><?= $book->created_at->format('Y-m-d H:i') ?></span>
                                            </div>
                                            <?php if(!is_null($book->updated_at)): ?>
                                            <div class="updated-at col-sm-6 col-xs-12">
                                                Updated at:
                                                <span><?= $book->updated_at->format('Y-m-d H:i') ?></span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="read-more">
                                        <button type="button" class="btn btn-sm btn-primary" disabled>Read More</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row pagination-row">
                <nav arial-label="Page Navigation">
                    <ul class="pagination">
                        <?php $max_pages = ceil($items_count / $items_per_page); 
                        for($i = 1; $i <= $max_pages; $i++): ?>
                        <li class="<?=$page == $i ? 'active' : ''; ?>">
                            <a href="<?= $this->url(['page' => $i]) ?>">
                            <?= $i ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </main>