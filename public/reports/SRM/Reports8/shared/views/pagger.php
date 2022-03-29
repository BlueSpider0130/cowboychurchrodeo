  <?php if ($_print_option !== 1 || !isset($_CLEANED["print"])) : ?>
        <tr >

            <td class="TableFooter">

                <div style="text-align: center;">


                    <span><?php echo escape((int) $fromRecordNumber); ?></span>
                    <?php echo escape($pager_to); ?><span><?php echo escape((int) $toRecordNumber); ?></span>
                    <?php echo escape($pager_of); ?> <span><?php
                        echo escape((int) $nRecords);
                        echo ' ' . escape($pager_records);
                        ?></span>

                    <div class="pages-num">
                        <a	class="firstPage" href="<?php echo escape($firstPage) ?>"></a>
                        <a class="prevPage" href="<?php echo escape($prevPage) ?>"></a>

    <?php echo escape($pager_page); ?> <?php echo escape((int) $currentPage) ?> <?php echo escape($pager_of); ?> <?php echo escape((int) $numberOfPages); ?>

                        <a class="nextPage" href="<?php echo escape($nextPage) ?>"></a>
                        <a class="lastPage" href="<?php echo escape($lastPage) ?>"></a>

                        <form method="get" action="<?php echo basename($_SERVER['PHP_SELF']); ?>" style="display: inline;">
                            <label><?php echo escape($Go_To_Page_lang); ?><input type="hidden" name="RequestToken"  value=<?php echo $request_token_value; ?> /> <input name="cp" style="width: 50px" value="<?php echo escape($currentPage) ?>" /></label>
                            <?php if ($url_param === 1701) { ?>
                                <input type="hidden" name="DebugMode7"  value="1701" />
    <?php } ?>

                        </form>
                    </div>
<?php endif; ?>



            </div>
        </td>

    </tr>
    <!-- end pagination block -->
