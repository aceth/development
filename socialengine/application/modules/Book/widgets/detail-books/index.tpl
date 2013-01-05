<?php if ($this->book) : ?>
    <div class='layout_middle'>
        <h2 style=" color:#123456;"> <?php echo $this->translate('%1$s\'s Book', $this->htmlLink($this->owner->getHref(), $this->owner->getTitle()))?></h2>
    </div>


    <?php if ($this->book->authorization()->isAllowed($this->viewer, 'edit') ) : ?>
    <div class="tip">
        <span>
        <?php echo $this->translate('You can edit book by %1$sEditing%2$s. or delete book by %3$sDeleting%4$s', '<a href="'.$this->url(array('action' => 'edit', 'book_id' => $this->book->book_id), 'book_specific', true).'">', '</a>', '<a href="'.$this->url(array('action' => 'delete', 'book_id' => $this->book->book_id), 'book_specific', true).'">', '</a>'); ?>
        </span>
    </div>
    <?php endif; ?>

    <div style="">
        <h3 style="color:#5F93B4;">
            <?php echo $this->book->getTitle(); ?>
        </h3>
    </div>
    <div class='book_image'>
        <?php echo $this->htmlLink($this->book->getHref(array('action' => 'view')), $this->itemPhoto($this->book, 'thumb.icon'), array('class' => 'thumb')) ?>
    </div>
    <div class="book_description">
        Description: <?php echo $this->book->description; ?>
    </div>
    <div class="book_author">
        Author: <?php echo $this->book->author; ?>
    </div>
    <div class="book_price">
        Price: <?php echo $this->book->price; ?>$
    </div>
    <div class="book_buy">
        <br />
        <form action="" method="post">
            Quantity:
            <select name="quantity">
                <?php
                    for ($i = 1; $i <= 10; $i++) {
                        echo "<option value='$i'>$i</option>";
                    }
                ?>
            </select>
            <input type="hidden" name="bookid" value="<?php echo $this->book->book_id;?>" />
            <button type="submit" name="buy">Add to cart</button>
        </form>
    </div>
    <div class="book_entrylist_date" style="margin: 5px;margin-left:0px; color:#7F7F7F;">
        <?php echo $this->translate('Posted by');?> <?php echo $this->htmlLink($this->owner->getHref(), $this->owner->getTitle()); ?>
        <?php echo $this->timestamp($this->book->posted_date); ?>
    </div>
    <br/>
    <?php echo $this->action("list", "comment", "core", array("type"=>"book", "id"=>$this->book->getIdentity())) ?>
<?php else : ?>
    <span style="font-size:15px;color:red">Book not found!</span>
<?php endif; ?>
<br />