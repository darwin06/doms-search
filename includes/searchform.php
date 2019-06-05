<?php
  if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

  /* *
   * GET the value of type_mime var
  */
  $type_mime = get_query_var('type_mime', false);

  /* *
   * Get options from Settings page of search plugin
  */
  $formatOptions = get_option( 'format_options' );

?>
<div id="search_form" class="container">
  <div class="row">
    <div class="col-sm-12 px-0">
      <form id="searchform" role="search" method="get" class="search-form"
        action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <?php
        $selectEnabled = 10;
        if( $formatOptions['template'] === 'select') { $selectEnabled = 7; }
        ?>
        <div class="form-group">
          <div class="search-bar">
            <div class="form-row">
              <div class="form-group col-md-<?php echo $selectEnabled; ?>">
                <div class="input-group search">
                  <input type="search" name="s" class="form-control search-field"
                    placeholder="<?php esc_attr_e('Search&hellip;', 'doms-search'); ?>"
                    value="<?php echo esc_attr( get_search_query() ); ?>" aria-describedby="Search Field"
                    title="<?php esc_attr_e('Search for:', 'doms-search'); ?>">
                </div>
              </div>
              <?php if( $formatOptions['template'] === 'select') { ?>
              <div class="form-group col-md-3">
                <div class="input-group input-group-append">
                  <select id="doms_search_typemedia" class="form-control" name="type_mime[]">
                    <option value="all" <?php selected( $type_mime[0], 'all' ); ?>>
                      <?php _e('All Results', 'doms-search') ?>
                    </option>
                    <?php if ($formatOptions['pdf_check']) { ?>
                    <option value="pdf" <?php selected( $type_mime[0], 'pdf' ); ?>><?php _e('PDFs','doms-search'); ?>
                    </option>
                    <?php } ?>
                    <?php if ($formatOptions['doc_check']) { ?>
                    <option value="doc" <?php selected( $type_mime[0], 'doc' ); ?>><?php _e('DOCs','doms-search'); ?>
                    </option>
                    <?php } ?>
                    <?php if ($formatOptions['img_check']) { ?>
                    <option value="img" <?php selected( $type_mime[0], 'img' ); ?>><?php _e('Images','doms-search'); ?>
                    </option>
                    <?php } ?>
                    <?php if ($formatOptions['video_check']) { ?>
                    <option value="video" <?php selected( $type_mime[0], 'video' ); ?>><?php _e('Videos','doms-search'); ?>
                    </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php } ?>
              <div class="form-group col-md-2">
                <input class="btn btn-primary btn-block" type="submit" value="<?php _e('Search', 'doms-search'); ?>">
              </div>
            </div>
          </div>
        </div>
        <?php
          $publish = false;
          $flag = false;
          foreach ($formatOptions as $key => $value) {
            if (!empty($value)) {
              $flag = true;
            }
          }
          if (!empty($flag)) {
            $publish = true;
          }
          if( $formatOptions['template'] === 'checkbox' && !empty($publish)) {
        ?>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <?php if ($formatOptions['pdf_check']) { ?>
              <label class="form-check-label doms-input-container">
                <input type="checkbox" name="type_mime[]" value="pdf" class="form-check-input" id="checkbox_pdf"
                  <?php if( isset($_GET['type_mime']) && is_array($_GET['type_mime'])){ checked( in_array('pdf', $_GET['type_mime']) ); } ?>>
                <span class="checkmark"></span>
                <i class="far fa-file-pdf"></i>
                <?php _e('PDFs', 'doms-search'); ?>
              </label>
              <?php } ?>
            </div>
            <div class="form-check form-check-inline">
              <?php if ($formatOptions['doc_check']) { ?>
              <label class="form-check-label doms-input-container">
                <input type="checkbox" name="type_mime[]" value="doc" class="form-check-input" id="checkbox_doc"
                  <?php if( isset($_GET['type_mime']) && is_array($_GET['type_mime'])){ checked( in_array('doc', $_GET['type_mime']) ); } ?>>
                <span class="checkmark"></span>
                <i class="far fa-file-alt"></i>
                <?php _e('DOCs', 'doms-search'); ?>
              </label>
              <?php } ?>
            </div>
            <div class="form-check form-check-inline">
              <?php if ($formatOptions['img_check']) { ?>
              <label class="form-check-label doms-input-container">
                <input type="checkbox" name="type_mime[]" value="img" class="form-check-input" id="checkbox_img"
                  <?php if( isset($_GET['type_mime']) && is_array($_GET['type_mime'])){ checked( in_array('img', $_GET['type_mime']) ); } ?>>
                <span class="checkmark"></span>
                <i class="far fa-file-image"></i>
                <?php _e('Images', 'doms-search'); ?>
              </label>
              <?php } ?>
            </div>
            <div class="form-check form-check-inline">
              <?php if ($formatOptions['video_check']) { ?>
              <label class="form-check-label doms-input-container">
                <input type="checkbox" name="type_mime[]" value="video" class="form-check-input" id="checkbox_video"
                  <?php if( isset($_GET['type_mime']) && is_array($_GET['type_mime'])){ checked( in_array('video', $_GET['type_mime']) ); } ?>>
                <span class="checkmark"></span>
                <i class="far fa-file-video"></i>
                <?php _e('Videos', 'doms-search'); ?>
              </label>
              <?php } ?>
            </div>
          </div>
        <?php
          }
        ?>
      </form>
    </div>
  </div>
</div>
