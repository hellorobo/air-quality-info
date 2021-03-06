<p></p>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h4>About</h4>
        <p>The page is based on the <a href="https://github.com/trekawek/air-quality-info">Air Quality Info</a> app.</p>
        <ul>
            <?php if (isset($currentDevice['contact_email'])): ?>
            <li><?php echo __('Contact info') ?>:
                <a href="mailto:<?php echo $currentDevice['contact_email'] ?>">
                <?php echo $currentDevice['contact_name'] ?>
                </a>
            </li>
            <?php endif ?>
            <li><a href="<?php echo l('tool', 'index'); ?>">Maintenance tools</a></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <small>Icons made by <a href="https://www.freepik.com/" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></small>
    </div>
</div>