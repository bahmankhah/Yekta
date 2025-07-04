<?php
if (!defined('ABSPATH')) { exit; }

global $wpdb;
$paged = max(1, intval($_GET['paged'] ?? 1));
$per_page = 20;
$offset = ($paged - 1) * $per_page;

$user_query = new WP_User_Query([
    'meta_key'   => 'sso_global_id',
    'number'     => $per_page,
    'offset'     => $offset,
]);
$users = $user_query->get_results();
$total_users = $user_query->get_total();

$audit_table = $wpdb->prefix . 'yekta_audit';
$today = current_time('Y-m-d');
$todays_logins = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$audit_table} WHERE type=%s AND DATE(created_at)=%s", 'login', $today));
$todays_creations = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$audit_table} WHERE type=%s AND DATE(created_at)=%s", 'created', $today));
$total_pages = ceil($total_users / $per_page);
?>
<div class="wrap">
    <h1>کاربران SSO</h1>
    <p>ورودهای امروز: <?php echo $todays_logins; ?> - کاربران جدید امروز: <?php echo $todays_creations; ?></p>
    <table class="widefat fixed">
        <thead>
            <tr>
                <th>نام کاربری</th>
                <th>نام نمایشی</th>
                <th>ایمیل</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo esc_html($user->user_login); ?></td>
                    <td><?php echo esc_html($user->display_name); ?></td>
                    <td><?php echo esc_html($user->user_email); ?></td>
                    <td><button class="button yekta-user-info-btn" data-id="<?php echo $user->ID; ?>">نمایش اطلاعات</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="tablenav">
        <div class="tablenav-pages">
            <?php
            echo paginate_links([
                'base'      => add_query_arg('paged','%#%'),
                'format'    => '',
                'prev_text' => '«',
                'next_text' => '»',
                'total'     => $total_pages,
                'current'   => $paged,
            ]);
            ?>
        </div>
    </div>
    <div id="yekta-user-info-modal" style="display:none;position:fixed;top:10%;left:50%;transform:translateX(-50%);background:#fff;border:1px solid #ccc;padding:20px;max-width:600px;max-height:80%;overflow:auto;z-index:10000;">
        <button id="yekta-close-modal" class="button">بستن</button>
        <pre id="yekta-user-info-content" style="white-space:pre-wrap"></pre>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
    const modal=document.getElementById('yekta-user-info-modal');
    const close=document.getElementById('yekta-close-modal');
    const content=document.getElementById('yekta-user-info-content');
    document.querySelectorAll('.yekta-user-info-btn').forEach(btn=>{
        btn.addEventListener('click',function(){
            const id=this.dataset.id;
            modal.style.display='block';
            content.textContent='در حال دریافت...';
            fetch(ajaxurl+'?action=yekta_get_user_info&user_id='+id)
                .then(r=>r.json())
                .then(d=>{content.textContent=JSON.stringify(d,null,2);})
                .catch(()=>{content.textContent='خطا در دریافت اطلاعات';});
        });
    });
    close.addEventListener('click',()=>{modal.style.display='none';});
});
</script>
