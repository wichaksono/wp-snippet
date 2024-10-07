# WP Bootstrap

### How to use
```html
<div class="wp-bootstrap">

  <!-- bootstrap component -->

</div>
```

### How to use on WP-Admin
```php
// register wp-bootstrap.css
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('wp-bootstrap', get_stylesheet_directory_uri() . '/lokasi/file/wp-bootstrap.css');

    // bootstrap js cdn
    wp_enqueue_script('popper-js', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js', ['jquery'], '2.11.8');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', ['jquery'], '5.3.3');
});

// register admin_page_menu
function custom_dashboard_page(): void
{
    
    add_menu_page(
        'Custom Page Title',   
        'Custom Menu',         
        'manage_options',      
        'custom-dashboard',    
        'custom_dashboard_content', 
        'dashicons-admin-generic',  
        6                       
    );
}
add_action('admin_menu', 'custom_dashboard_page');

// render page
function custom_dashboard_content() {
    ?>
    <div class="wrap wp-bootstrap">
        <h1>Ini adalah halaman kustom di dashboard</h1>
        <p>Selamat datang di halaman kustom yang ditambahkan ke dashboard WordPress Anda!</p>

        <button type="button" class="btn btn-primary">Primary</button>
        <button type="button" class="btn btn-secondary">Secondary</button>
        <button type="button" class="btn btn-success">Success</button>
        <button type="button" class="btn btn-danger">Danger</button>
        <button type="button" class="btn btn-warning">Warning</button>
        <button type="button" class="btn btn-info">Info</button>
        <button type="button" class="btn btn-light">Light</button>
        <button type="button" class="btn btn-dark">Dark</button>

        <button type="button" class="btn btn-link">Link</button>

        <div class="py-2 border-bottom mb-2 border-dark-subtle"></div>

        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Dropdown button
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
        </div>

        <div class="py-2 border-bottom mb-2 border-dark-subtle"></div>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
          Launch static backdrop modal
        </button>
        
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
              </div>
            </div>
          </div>
        </div>

        <div class="py-2 border-bottom mb-2 border-dark-subtle"></div>

        <label for="customRange1" class="form-label">Example range</label>
        <input type="range" class="form-range" id="customRange1">

        <div class="py-2 border-bottom mb-2 border-dark-subtle"></div>

        <div class="toast d-block" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="https://placehold.co/24" class="rounded me-2" alt="...">
                <strong class="me-auto">Bootstrap</strong>
                <small>11 mins ago</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>
    <?php
}
```
