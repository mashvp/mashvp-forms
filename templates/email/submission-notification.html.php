<?php
  /**
   * Email template courtesy of https://github.com/leemunroe/responsive-html-email-template
   */

  use Mashvp\Forms\Submission;

?>

<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?= $submission->getTitle() ?></title>
    <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */

      /*All the styling goes here*/

      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%;
      }

      body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
      }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%;
      }

      table td {
        font-family: sans-serif;
        font-size: 14px;
        vertical-align: top;
      }

      table.submission-fields {
        margin: 30px 0;
        border-collapse: collapse;
      }

      table.submission-fields th,
      table.submission-fields td {
        padding: 8px 22px;
      }

      table.submission-fields table th,
      table.submission-fields table td {
        padding: 1px;
      }

      table.submission-fields thead {
        background: #efefef;
      }

      table.submission-fields tbody tr th {
        width: 33%;
        text-align: right;
        font-weight: normal;
      }

      table.submission-fields tbody tr td dd {
        margin-left: 0;
      }

      table.submission-fields tbody tr td dd[data-type="textarea"] {
        white-space: pre-line;
      }

      table.submission-fields th,
      table.submission-fields td {
        vertical-align: middle;
      }

      table.submission-fields {
        border-color: white;
      }

      table.submission-fields tr,
      table.submission-fields th,
      table.submission-fields td {
        border-color: #ccc;
      }

      table.submission-fields dd[data-type="range"] {
        position: relative;
      }

      table.submission-fields dd[data-type="range"] .value-range,
      table.submission-fields dd[data-type="range"] input[type="range"] {
        width: 100%;
      }

      table.submission-fields dd[data-type="range"] .value-range span {
        position: relative;
        display: inline-block;
      }

      table.submission-fields dd[data-type="range"] .value-range span.current-value {
        font-weight: bold;
      }

      table.submission-fields dd[data-type="range"] .value-range span.max {
        float: right;
      }

      table.submission-fields table.choice-list th {
        width: 100%;

        text-align: left;
        font-size: 10px;
        color: #333;
      }

      table.submission-fields table.choice-list td {
        text-align: center;
      }

      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

      .body {
        background-color: #f6f6f6;
        width: 100%;
      }

      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        margin: 0 auto !important;
        /* makes it centered */
        max-width: 920px;
        padding: 10px;
        width: 920px;
      }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        margin: 0 auto;
        max-width: 920px;
        padding: 10px;
      }

      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #ffffff;
        border-radius: 3px;
        width: 100%;
      }

      .wrapper {
        box-sizing: border-box;
        padding: 20px;
      }

      .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
      }

      .footer {
        clear: both;
        margin-top: 10px;
        text-align: center;
        width: 100%;
      }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 12px;
          text-align: center;
      }

      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        margin-bottom: 30px;
      }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize;
      }

      h3 {
        margin-bottom: 10px;
      }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        margin-bottom: 15px;
      }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px;
      }

      a {
        color: #3498db;
        text-decoration: underline;
      }

      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto;
      }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          text-align: center;
      }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
      }

      .btn-primary table td {
        background-color: #3498db;
      }

      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff;
      }

      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0;
      }

      .first {
        margin-top: 0;
      }

      .align-center {
        text-align: center;
      }

      .align-right {
        text-align: right;
      }

      .align-left {
        text-align: left;
      }

      .clear {
        clear: both;
      }

      .mt0 {
        margin-top: 0;
      }

      .mb0 {
        margin-bottom: 0;
      }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0;
      }

      .powered-by {
        color: #cccccc !important;
        font-size: 10px !important;
      }

      .powered-by a {
        text-decoration: none;
      }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        margin: 20px 0;
      }

      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important;
        }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important;
        }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important;
        }
        table[class=body] .content {
          padding: 0 !important;
        }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important;
        }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important;
        }
        table[class=body] .btn table {
          width: 100% !important;
        }
        table[class=body] .btn a {
          width: 100% !important;
        }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important;
        }
      }

      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%;
        }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%;
        }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important;
        }
        #MessageViewBody a {
          color: inherit;
          text-decoration: none;
          font-size: inherit;
          font-family: inherit;
          font-weight: inherit;
          line-height: inherit;
        }
        .btn-primary table td:hover {
          background-color: #34495e !important;
        }
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important;
        }
      }

    </style>
  </head>
  <body class="">
    <span class="preheader"><?= $submission->getTitle() ?></span>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">

            <!-- START CENTERED WHITE CONTAINER -->
            <table role="presentation" class="main">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper">
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <h3><?= _x('Hi there,', 'Email greeting', 'mashvp-forms') ?></h3>
                        <p>
                          <?=
                            sprintf(
                                /* translators: %s form title in quotation marks */
                                _x('You have received a new message from your form %s.', 'Email submission explanation', 'mashvp-forms'),
                                sprintf(
                                  /* translators: %s any content between quotation marks */
                                  _x('“%s”', 'Quotation marks', 'mashvp-forms'),
                                    $form->getTitle()
                                )
                            )
                          ?>
                        </p>

                        <table class="submission-fields" border="1" cellpadding="6" cellspacing="0">
                          <thead>
                            <tr>
                              <th colspan="2"><?= $submission->getTitle() ?></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($submission->getFields() as $field): ?>
                              <?php
                                $skip_fields = ['submit', 'reset', 'button', 'message', 'horizontal-separator'];

                                if (!in_array($field['type'], $skip_fields)):
                              ?>
                                <tr>
                                  <th><?= $field['label'] ?></th>
                                  <td><?= Submission::renderField($field, $form->getID(), ['context' => 'email']) ?></td>
                                </tr>
                              <?php endif ?>
                            <?php endforeach ?>
                          </tbody>
                        </table>

                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                          <tbody>
                            <tr>
                              <td align="left">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                      <td>
                                        <a href="<?= $submission->getAdminPermalink() ?>" target="_blank">
                                          <?= _x('See submission', 'Email submission call-to-action', 'mashvp-forms') ?>
                                        </a>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <p>&nbsp;</p>
                        <p><em>— Mashvp Forms</em></p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>
            <!-- END CENTERED WHITE CONTAINER -->

            <!-- START FOOTER -->
            <div class="footer">
              <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="content-block">
                    <span class="apple-link">Mashvp Forms</span><br/>
                    <span>
                      <?=
                        sprintf(
                          /* translators: %s link to "form settings" */
                          __('You can disable email notifications from the %s.', 'mashvp-forms'),
                            sprintf(
                                '<a href="%1$s">%2$s</a>',
                                esc_html($form->getAdminPermalink()),
                                __('form settings', 'mashvp-forms')
                            )
                        )
                      ?>
                    </span>
                  </td>
                </tr>
                <tr>
                  <td class="content-block powered-by">
                    <?=
                      sprintf(
                          /* translators: %s phonetic transcription of Mashvp */
                          _x("It's pronounced %s.", 'Sentence about the phonetic transcription of Mashvp', 'mashvp-forms'),
                          sprintf(
                              '<em>%s</em>',
                              _x('mashup', 'Phonetic transcription of Mashvp', 'mashvp-forms')
                          )
                      )
                    ?>
                  </td>
                </tr>
              </table>
            </div>
            <!-- END FOOTER -->

          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>