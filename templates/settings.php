<div class="wrap">
    <h2>Windows Azure End User Analytics (Unofficial) Settings</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('windows-azure-end-user-analytics-options'); ?>
        <?php @do_settings_fields('windows-azure-end-user-analytics-options'); ?>

        <table class="form-table">  
            <tr valign="top">
                <th scope="row"><label for="setting_a">AppInsights App Key</label></th>
                <td><input type="text" name="app_insights_app_key" id="app_insights_app_key" value="<?php echo get_option('app_insights_app_key'); ?>" /></td>
            </tr>
        </table>

        <?php @submit_button(); ?>
    </form>
</div>