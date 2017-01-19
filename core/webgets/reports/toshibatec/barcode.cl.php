<?php

/* Print a barcode

PROPERTIES SUMMARY (Tecnical informations are at the bottom)
--------------------------------------------------------------------------------

  left         : X-coordinate of the print origin of bar code
  top          : Y-coordinate of the print origin of bar code
  type         : Type of bar code
  type_options : Specific options by barcode type
  style        : Specific style options by barcode type
  data         : Data string to be printed
  increment    : increment step. The letters in the text will be removed
                 and remaining digit will be treated as an unique number
                 to be incremented (where applicable)
  zero_supp    : Zero suppression, effective only for WPC, MSI and GS1 Databar
  rotation     :  Rotational angle (0 -> 0°,1 -> 90°,2 -> 180°,3 -> 270°)
  field        : field/fields linked to the data source in the root iterator
  field_format : mask to be applied to the data obtained from fields
  connection   : Connection setting (for multiple 2d barcodes) (not yet implemented)
*/


class reports_toshibatec_barcode
{
  public $req_attribs = array(
    'left',
    'top',
    'type',
    'type_options',
    'style',
    'increment',
    'zero_supp',
    'rotation',
    'data',
    'field',
    'field_format',
    'connection'
  );

  function __define(&$_)
  {
    /* sets the default values */
    $default                = array();
    $default['zero_supp'][] = "0";
    $default['rotation'][]  = "0";

    foreach ($default as $key => $value)
      foreach ($value as $local)
      if ($local !== null && !isset($this->$key)) $this->$key=$local;
  }


  function __flush (&$_)
  {
    /* iteration loop sensing */
    if(!isset(_w('root')->counters['barcode'])) {
      _w('root')->counters['barcode'] = 1;
    }

    /* barcode number limit reached */
    if(_w('root')->counters['barcode'] > 31)
      die('Number of barcodes exceeds the allowed number (32)');

    /* Prepare the barcode command array */
    $this->barcode_command = array();

    /* set barcode index */
    $this->barcode_command[0] = "XB" . sprintf("%02d", _w('root')->counters['barcode']);

/******************************************************************************/
/* positioning */
/******************************************************************************/

    /* set barcode left and top */
    $this->barcode_command[1]  = sprintf("%04d", round(($this->left + $this->parent->offsetLeft) * 10));
    $this->barcode_command[2]  = sprintf("%04d", round(($this->top + $this->parent->offsetTop) * 10));


/******************************************************************************/
/* Barcode family                                                             */
/******************************************************************************/

    $bcode_family_def = array(
      '0' => array('WPC','JAN8, EAN8'),
      '5' => array('WPC','JAN13, EAN13'),
      '6' => array('WPC','UPC-E'),
      '7' => array('WPC','EAN13 + 2 digits'),
      '8' => array('WPC','EAN13 + 5 digits'),
      '9' => array('WPC','CODE128 (with auto code selection)'),
      'A' => array('WPC','CODE128 (without auto code selection)'),
      'C' => array('WPC','CODE93'),
      'G' => array('WPC','UPC-E + 2 digits'),
      'H' => array('WPC','UPC-E + 5 digits'),
      'I' => array('WPC','EAN8 + 2 digits'),
      'J' => array('WPC','EAN8 + 5 digits'),
      'K' => array('WPC','UPC-A'),
      'L' => array('WPC','UPC-A + 2 digits'),
      'M' => array('WPC','UPC-A + 5 digits'),
      'N' => array('WPC','UCC/EAN128'),
      'R' => array('PostalWPC','Customer bar code (Postal code for Japan)'),
      'S' => array('PostalWPC','Highest priority customer bar code (Postal code for Japan)'),
      'U' => array('PostalWPC','POSTNET (Postal code for U.S)'),
      'V' => array('PostalWPC','RM4SCC (ROYAL MAIL 4 STATE CUSTOMER CODE) (Postal code for U.K)'),
      'W' => array('PostalWPC','KIX CODE (Postal code for Belgium)'),
      '1' => array('MSI','MSI'),
      '2' => array('MSI','Interleaved 2 of 5 (ITF)'),
      '3' => array('MSI','CODE39 (standard)'),
      '4' => array('MSI','NW7'),
      'B' => array('MSI','CODE39 (full ASCII)'),
      'O' => array('MSI','Industrial 2 of 5'),
      'a' => array('MSI','MATRIX 2 of 5 for NEC'),
      'b' => array('GS1 DataBar','GS1 DataBar (RSS)'),
      'Q' => array('DataMatrix','Data Matrix (Two-dimensional code)'),
      'P' => array('PDF417','PDF417 (Two-dimensional code)'),
      'X' => array('MicroPDF417','MicroPDF417 (Two-dimensional code)'),
      'Y' => array('CPCode','CP code (Two-dimensional code)'),
      'T' => array('QRCode','QR code (Two-dimensional code)'),
      'Z' => array('MaxiCode','MaxiCode (Two-dimensional code)'),
    );

    $this->family = $bcode_family_def[$this->type][0];
    $description  = $bcode_family_def[$this->type][1];

    /* error breakpoint */
    if($this->family == null){
      $message = array('message' => "FATAL : BARCODE_FAMILY_MISMATCH");
      _call('utils.write_debug', $message);
      die();
    }

    else {
      $message = array('message' => "selected family : " . $this->family);
      _call('utils.write_debug', $message);
      $message = array('message' => "selected type : " . $description);
      _call('utils.write_debug', $message);

      /* set barcode family */
      $this->barcode_command[3] = $this->type;
    };


/******************************************************************************/
/* Barcode family options                                                     */
/******************************************************************************/

    $config = array(
      'WPC',
      'PostalWPC',
      'MSI',
      'GS1 DataBar',
      'DataMatrix',
      'PDF417',
      'MicroPDF417',
      'QRCode',
      'MaxiCode',
      'CPcode'
    );

    /* WPC ********************************************************************/
    $config['WPC'] = array(
      /* e - Type of check digit */
      array(
        'type'     => 'options',
        'required' => TRUE,
        'default'  => 2,
        'options'  => array(
          '1' => "Without attaching check digit",
          '2' => "Check digit check",
          '3' => "Check digit automatic attachment (1)",
          '4' => "Check digit automatic attachment (2)",
          '5' => "Check digit automatic attachment (3)"),
        'message'  => "Type of check digit",
        'fatal'    => "BARCODE_TYPE_OF_CHECK_DIGIT_MISMATCH",
        'format'   => "%01d",
        'command'  => 4
      )
    );

    /* WPC ********************************************************************/
    $config['PostalWPC'] = array(
      /* e - Type of check digit */
      array(
        'type' => 'forced',
        'value' => '3',
        'message' => 'Type of check digit : Check digit automatic attachment (1) (forced)',
        'command' => 4
      ),
    );

    /* MSI ********************************************************************/
    $config['MSI'] = array(
      /* e - Type of check digit */
      array(
        'type'     => 'options',
        'required' => FALSE,
        'default'  => 1,
        'options'  => array(
          '1' => "Without attaching check digit",
          '2' => "Check digit check",
          '3' => "Check digit automatic attachment (1)",
          '4' => "Check digit automatic attachment (2)",
          '5' => "Check digit automatic attachment (3)"),
        'message'  => "Type of check digit",
        'fatal'    => "BARCODE_TYPE_OF_CHECK_DIGIT_MISMATCH",
        'format'   => "%01d",
        'command'  => 4
      ),

      /* e - Designates the attachment of start/stop code */
      array(
        'type'     => 'options',
        'required' => FALSE,
        'default'  => NULL,
        'options'  => array(
          'T' => "Attachment of start code only",
          'P' => "Attachment of stop code only",
          'N' => "Start/stop code unattached",),
        'message'  => "start/stop code",
        'fatal'    => "BARCODE_START_STOP_CODE_OPTION_MISMATCH",
        'format'   => "%01s",
        'command'  => 15
      )
    );

    /* GS1 Databar ************************************************************/
    $config['GS1 DataBar'] = array(
      /* e - Barcode version */
      array(
        'type'     => 'options',
        'required' => TRUE,
        'default'  => 2,
        'options'  => array(
          '1' => "GS1 DataBar (Omnidirectional/Truncated)",
          '2' => "GS1 DataBar Stacked",
          '3' => "GS1 DataBar Stacked Omnidirectional",
          '4' => "GS1 DataBar Limited",
          '5' => "GS1 DataBar Expanded",
          '6' => "GS1 DataBar Expanded Stacked"),
        'message'  => "Barcode version",
        'fatal'    => "BARCODE_VERSION_MISMATCH",
        'format'   => "%01d",
        'command'  => 4
      )
    );

    /* Data Matrix ************************************************************/
    $config['DataMatrix'] = array(
      /* ECC type */
      array(
        'type'     => 'options',
        'required' => TRUE,
        'default'  => 20,
        'options'  => array(
          '0' => "ECC0",
          '1' => "ECC50",
          '4' => "ECC50",
          '5' => "ECC50",
          '6' => "ECC80",
          '7' => "ECC80",
          '8' => "ECC80",
          '9' => "ECC100",
          '10' => "ECC100",
          '11' => "ECC140",
          '12' => "ECC140",
          '13' => "ECC140",
          '14' => "ECC140",
          '20' => "ECC200"),
        'message'  => "ECC Type",
        'fatal'    => "BARCODE_ECC_TYPE_MISMATCH",
        'format'   => "%02d",
        'command'  => 4
      ),

      /* Format ID */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 6,
        'min'      => 0,
        'max'      => 6,
        'message'  => "Format ID",
        'fatal'    => "BARCODE_WRONG_FORMAT_ID",
        'format'   => "%02d",
        'command'  => 6
      )
    );

    /* PDF417 *****************************************************************/
    $config['PDF417'] = array(
      /* Security level */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 0,
        'min'      => 0,
        'max'      => 8,
        'message'  => "Security level",
        'fatal'    => "BARCODE_WRONG_SECURITY_LEVEL",
        'format'   => "%02d",
        'command'  => 4
      )
    );

    /* MicroPDF417 ************************************************************/
    $config['MicroPDF417'] = array(
      /* e - Type of check digit */
      array(
        'type' => 'forced',
        'value' => '00',
        'message' => 'Security level : 00 Fixed (forced)',
        'command' => 4
      ),
    );

    /* QRCode *****************************************************************/
    $config['QRCode'] = array(
      /* e - Error correction level */
      array(
        'type'     => 'options',
        'required' => TRUE,
        'default'  => "L",
        'options'  => array(
          'L' => "High density level",
          'M' => "Standard level",
          'Q' => "Reliability level",
          'H' => "High reliability level"),
        'message'  => "Error correction level",
        'fatal'    => "BARCODE_ERROR_CORRECTION_LEVEL_MISMATCH",
        'format'   => "%01s",
        'command'  => 4
      ),

      /* g - Selection of mode */
      array(
        'type'     => 'options',
        'required' => TRUE,
        'default'  => "A",
        'options'  => array(
          'M' => "Manual mode",
          'A' => "Automatic mode"),
        'message'  => "Selection of mode",
        'fatal'    => "BARCODE_SELECTION_OF_MODE_MISMATCH",
        'format'   => "%01s",
        'command'  => 6
      ),

      /* Mi - Selection of model */
      array(
        'type'     => 'options',
        'required' => TRUE,
        'default'  => "2",
        'options'  => array(
          '1' => "Model 1",
          '2' => "Model 1"),
        'message'  => "Selection of model",
        'fatal'    => "BARCODE_SELECTION_OF_MODEL_MISMATCH",
        'format'   => "M%01s",
        'command'  => 8
      ),

      /* Kj - Mask Number */
      array(
        'type'     => 'options',
        'required' => FALSE,
        'default'  => NULL,
        'options'  => array(
          '0' => "0",
          '1' => "1",
          '2' => "2",
          '3' => "3",
          '4' => "4",
          '5' => "5",
          '6' => "6",
          '7' => "7",
          '8' => "No Mask"),
        'message'  => "Mark number",
        'fatal'    => "BARCODE_WRONG_MASK_NUMBER",
        'format'   => "K%01d",
        'command'  => 9
      ),
    );

    /* MaxiCode ***************************************************************/
    $config['MaxiCode'] = array(
      /* e - Mode selection */
      array(
        'type'     => 'options',
        'required' => FALSE,
        'default'  => 0,
        'options'  => array(
          '0' => "Mode 2 or Mode 3(*)",
          '1' => "Mode 4",
          '2' => "Mode 2",
          '3' => "Mode 3",
          '4' => "Mode 4",
          '5' => "Mode 2 or Mode 3 (*)",
          '6' => "Mode 6",
          '7' => "Mode 2 or Mode 3 (*)",
          '8' => "Mode 2 or MODE 3 (*)",
          '9' => "Mode 2 or MODE 3 (*)"),
        'message'  => "Mode selection",
        'fatal'    => "BARCODE_MODE_SELECTION_MISMATCH",
        'format'   => "%01d",
        'command'  => 4
      ),
    );

    /* CP Code ************************************************************/
    $config['CPCode'] = array(
      /* ECC type */
      array(
        'type'     => 'options',
        'required' => TRUE,
        'default'  => 5,
        'options'  => array(
          '0' => "No designation",
          '1' => "10%",
          '2' => "20%",
          '3' => "30%",
          '4' => "40%",
          '5' => "50%"),
        'message'  => "ECC level",
        'fatal'    => "BARCODE_ECC_LEVEL_MISMATCH",
        'format'   => "%01d",
        'command'  => 4
      ),
    );

    $this->options_helper($config, @$this->type_options);


/******************************************************************************/
/* Barcode style                                                              */
/******************************************************************************/

    $config = array(
      'WPC',
      'PostalWPC',
      'MSI',
      'GS1 DataBar',
      'DataMatrix',
      'PDF417',
      'MicroPDF417',
      'QRCode',
      'MaxiCode',
      'CPcode'
    );

    /* WPC ********************************************************************/
    $config['WPC'] = array(
      /* ff - 1-module width */
      array(
        'type'     => 'range',
        'required' => 1,
        'default'  => 2,
        'min'      => 1,
        'max'      => 15,
        'message'  => "1-module width",
        'fatal'    => "BARCODE_1_MODULE_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 5
      ),

      /* llll - Height of the bar code */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 100,
        'min'      => 0,
        'max'      => 1000,
        'message'  => "Height of the bar code",
        'fatal'    => "BARCODE_HEIGHT_OUT_OF_RANGE",
        'format'   => "%04d",
        'command'  => 7
      ),

      /* ooo - Length of WPC guard bar */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 20,
        'min'      => 0,
        'max'      => 100,
        'message'  => "Length of WPC guard bar",
        'fatal'    => "BARCODE_GUARD_BAR_OUT_OF_RANGE",
        'format'   => "%03d",
        'command'  => 9
      ),

      /* p - numerals under bars */
      array(
        'type'     => 'options',
        'required' => FALSE,
        'default'  => 1,
        'options'  => array(
                      '0' => "Not Printed",
                      '1' => "Printed"),
        'message'  => "Numerals under bars",
        'fatal'    => "BARCODE_NUMERALS_UNDER_BARS_BAD_OPTION",
        'format'   => "%01d",
        'command'  => 10
      ),
    );

    /* Postal WPC & GS1 Databar ***********************************************/
    $config['PostalWPC'] = array(
      /* ff - 1-module width */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 2,
        'min'      => 1,
        'max'      => 15,
        'message'  => "1-module width",
        'fatal'    => "BARCODE_MODULE_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 5
      ),

      /* llll - Height of the bar code */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 20,
        'min'      => 0,
        'max'      => 1000,
        'message'  => "Height of the bar code",
        'fatal'    => "BARCODE_HEIGHT_OUT_OF_RANGE",
        'format'   => "%04d",
        'command'  => 7
      ),
    );

    $config['GS1 DataBar'] = $config['PostalWPC'];


    /* MSI ********************************************************************/
    $config['MSI'] = array(
      /* ff - Narrow bar width */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 2,
        'min'      => 1,
        'max'      => 99,
        'message'  => "Narrow bar width",
        'fatal'    => "BARCODE_NARROW_BAR_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 5
      ),

      /* gg - Narrow space width */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 2,
        'min'      => 1,
        'max'      => 99,
        'message'  => "Narrow or element-to-element space width",
        'fatal'    => "BARCODE_NARROW_E2E_SPACE_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 6
      ),

      /* hh - Wide bar width */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 4,
        'min'      => 1,
        'max'      => 99,
        'message'  => "Wide bar width",
        'fatal'    => "BARCODE_WIDE_BAR_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 7
      ),

      /* ii - Wide space width */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 4,
        'min'      => 1,
        'max'      => 99,
        'message'  => "Wide space width (ineffective if industrial 2 of 5)",
        'fatal'    => "BARCODE_WIDE_SPACE_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 8
      ),

      /*jj - Char-to-char space width */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 1,
        'min'      => 1,
        'max'      => 99,
        'message'  => "Char-to-char space width (ineffective if MSI or ITF)",
        'fatal'    => "BARCODE_CHAR2CHAR_SPACE_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 9
      ),

      /* llll - Height of the bar code */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 100,
        'min'      => 1,
        'max'      => 1000,
        'message'  => "Height of the bar code",
        'fatal'    => "BARCODE_HEIGHT_OUT_OF_RANGE",
        'format'   => "%04d",
        'command'  => 11
      ),

      /* p - numerals under bars */
      array(
        'type'     => 'options',
        'required' => FALSE,
        'default'  => 1,
        'options'  => array(
                      '0' => "Not Printed",
                      '1' => "Printed"),
        'message'  => "Numerals under bars",
        'fatal'    => "BARCODE_NUMERALS_UNDER_BARS_BAD_OPTION",
        'format'   => "%01d",
        'command'  => 13
      ),
    );

    /* DataMatrix *************************************************************/
    $config['DataMatrix'] = array(
      /* ff - 1-cell width */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 2,
        'min'      => 0,
        'max'      => 99,
        'message'  => "1-cell width",
        'fatal'    => "BARCODE_1_CELL_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 5
      ),

      /* iii and jjj - No. of cells in X/Y dir */
      array(
        'type'     => 'range',
        'required' => FALSE,
        'default'  => NULL,
        'min'      => 0,
        'max'      => 144,
        'message'  => "No. of cells in X dir",
        'fatal'    => "BARCODE_NO_OF_CELLS_X_DIR_OUT_OF_RANGE",
        'format'   => "%03d",
        'command'  => "8a"
      ),

      array(
        'type'     => 'range',
        'required' => FALSE,
        'default'  => NULL,
        'min'      => 0,
        'max'      => 144,
        'message'  => "No. of cells in Y dir",
        'fatal'    => "BARCODE_NO_OF_CELLS_Y_DIR_OUT_OF_RANGE",
        'format'   => "%03d",
        'command'  => "8b"
      ),
    );

    /* PDF417 *****************************************************************/
    $config['PDF417'] = array(
      /* ff - 1-module width */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 1,
        'min'      => 1,
        'max'      => 10,
        'message'  => "1-module width",
        'fatal'    => "BARCODE_MODULE_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 5
      ),

      /* gg - No. of columns (strings) */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 0,
        'min'      => 0,
        'max'      => 30,
        'message'  => "1-module width",
        'fatal'    => "BARCODE_MODULE_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 6
      ),

      /* iiii - Height of the bar code */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 5,
        'min'      => 0,
        'max'      => 100,
        'message'  => "Height of the bar code",
        'fatal'    => "BARCODE_HEIGHT_OUT_OF_RANGE",
        'format'   => "%04d",
        'command'  => 8
      ),
    );

    /* MicroPDF417 ************************************************************/
    $config['MicroPDF417'] = array(
      /* ff - 1-module width */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 2,
        'min'      => 1,
        'max'      => 10,
        'message'  => "1-module width",
        'fatal'    => "BARCODE_MODULE_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 5
      ),

      /* gg - No. of columns (strings) */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 0,
        'min'      => 0,
        'max'      => 38,
        'message'  => "1-module width",
        'fatal'    => "BARCODE_MODULE_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 6
      ),

      /* iiii - Height of the bar code */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 5,
        'min'      => 0,
        'max'      => 100,
        'message'  => "Height of the bar code",
        'fatal'    => "BARCODE_HEIGHT_OUT_OF_RANGE",
        'format'   => "%04d",
        'command'  => 8
      ),
    );


    /* QR Code ****************************************************************/
    $config['QRCode'] = array(
      /* ff - 1-cell width */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 2,
        'min'      => 0,
        'max'      => 52,
        'message'  => "1-cell width",
        'fatal'    => "BARCODE_1_CELL_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 5
      ),
    );

    /* MaxiCode ***************************************************************/
    $config['MaxiCode'] = array(
      array(
        'type'     => 'options',
        'required' => FALSE,
        'default'  => 0,
        'options'  => array(
          '0' => "No attachment of Zipper block and Contrast block",
          '1' => "Attachment of Zipper block and Contrast block",
          '2' => "Attachment of Zipper block",
          '3' => "Attachment of Contrast block"),
        'message'  => "Attachment of Zipper block and Contrast block",
        'fatal'    => "BARCODE_WRONG_ATTACHMENT_OPTION",
        'format'   => "Z%01d",
        'command'  => 6
      )
    );

    /* CP Code ****************************************************************/
    $config['CPCode'] = array(
      /* ff - 1-cell width */
      array(
        'type'     => 'range',
        'required' => TRUE,
        'default'  => 2,
        'min'      => 0,
        'max'      => 99,
        'message'  => "1-cell width",
        'fatal'    => "BARCODE_1_CELL_WIDTH_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => 5
      ),

      /* g - No. of character bits */
      array(
        'type'     => 'options',
        'required' => FALSE,
        'default'  => 0,
        'options'  => array(
          '0' => "Automatically set",
          'A' => "8 bits"),
        'message'  => "No. of character bits",
        'fatal'    => "BARCODE_WRONG_CHARACTER_BITS",
        'format'   => "%01d",
        'command'  => 6
      ),

      /* iii and jjj - No. of cells in X/Y dir */
      array(
        'type'     => 'range',
        'required' => FALSE,
        'default'  => NULL,
        'min'      => 3,
        'max'      => 22,
        'message'  => "No. of code char in X dir",
        'fatal'    => "BARCODE_NO_OF_CODE_CHAR_X_DIR_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => "8a"
      ),

      array(
        'type'     => 'range',
        'required' => FALSE,
        'default'  => NULL,
        'min'      => 2,
        'max'      => 22,
        'message'  => "No. of code char in Y dir",
        'fatal'    => "BARCODE_NO_OF_CODE_CHAR_Y_DIR_OUT_OF_RANGE",
        'format'   => "%02d",
        'command'  => "8b"
      ),
    );

    $this->options_helper($config, @$this->style);


/******************************************************************************/
/* Barcode data                                                               */
/******************************************************************************/

    /* set data depending by the presence of 'field' property */
    if(isset($this->field)){
      $field        = explode(',', $this->field);
      $field_format = (isset($this->field_format) ? $this->field_format : '{0}');

      foreach($field as $key => $param) {
        $param       = explode(':', $param);

        /* if no record on server resultset or forcing sending field to client
         * send fields definition to client */
        if(!isset(_w($param[0])->current_record) || isset($this->send_field))
          $cfields[] = $field[$key];

        $field[$key] = array_get_nested
                       (_w($param[0])->current_record, $param[1]);

      }

      $data = preg_replace_callback(
        '/\{(\d+)\}/',
        function($match) use ($field) {
          return $field[$match[1]];
        },
        $field_format
      );
    }

    /* else get data and try to compute the auto increment */
    /* TODO : NOT YET TESTED */
    else if (isset($this->increment)) {
      $increment = str_split($this->increment);

      if(is_numeric($increment[0]))
        $inc_direction = '+';

      else
        $inc_direction = array_shift($increment);

      $increment = (int)implode('', $increment);
      $row_incr  = $increment * _w('root')->labels;
      $row_incr  = $inc_direction . sprintf("%010d", $row_incr);

      $data = $this->calc_increment($this->data,
                              (_w('root')->clabel - 1) * $increment);
    }

    /* simply put the data content as is */
    else $data = $this->data;

    /* Issue an error if the data exeed the allowed by barcode type */
    $options = array(
      'WPC'         => 126,
      'PostalWPC'   => 126,
      'MSI'         => 126,
      'GS1 DataBar' => 126,
      'DataMatrix'  => 2000,
      'PDF417'      => 2000,
      'MicroPDF417' => 366,
      'QRCode'      => 2000,
      'CPCode'      => 473,
      'MaxiCode'    => 93
    );

    if (strlen($data) > $options[$this->family]){
      $message = array('message' =>
        "FATAL : BARCODE_DATA_EXEED_THE_MAXIMUM_ALLOWED");
      _call('utils.write_debug', $message);
      die();
    }


/******************************************************************************/
/* Command postprocess                                                        */
/******************************************************************************/

    /* put the increment field (where provided) */
    if (!isset($row_incr)) $row_incr = "+0000000000";
    if ($this->family == 'WPC') $this->barcode_command[8] = $row_incr;
    if ($this->family == 'MSI') $this->barcode_command[12] = $row_incr;
    if ($this->family == 'GS1 DataBar') $this->barcode_command[8] = $row_incr;

    /* zero suppression (where provided) */
    $zero_supp = sprintf("%02d",$this->zero_supp);
    if ($this->family == 'WPC') $this->barcode_command[11] = $zero_supp;
    if ($this->family == 'MSI') $this->barcode_command[14] = $zero_supp;
    if ($this->family == 'GS1 DataBar') $this->barcode_command[9] = $zero_supp;

    /* rotation (where provided) */
    $angles = array('0','90','180','270');
    if($this->rotation < 0 || $this->rotation > 3) {
      $message = array('message' => "FATAL : BARCODE_WRONG_ROTATIONAL_ANGLE");
      _call('utils.write_debug', $message);
    }

    else {
      if ($this->family == 'WPC') $cpos = 6;
      if ($this->family == 'PostalWPC') $cpos = 6;
      if ($this->family == 'GS1 DataBar') $cpos = 6;
      if ($this->family == 'MSI') $cpos = 10;
      if ($this->family == 'DataMatrix') $cpos = 7;
      if ($this->family == 'PDF417') $cpos = 7;
      if ($this->family == 'MicroPDF417') $cpos = 7;
      if ($this->family == 'QRCode') $cpos = 7;
      if ($this->family == 'CPCode') $cpos = 7;
      if(isset($cpos)) {
        $this->barcode_command[$cpos] = $this->rotation;
        $message = array('message' =>
                          "Rotational angle : " . $angles[$this->rotation]);
      }

      else {
        $message = array('message' => "Rotational angle : not applicable");
      }

      _call('utils.write_debug', $message);
    }

    /* Join cell dimensions if Datamatrix */
    if ($this->family == 'DataMatrix' || $this->family == 'CPCode') {
      if(isset($this->barcode_command['8a']) &&
            isset($this->barcode_command['8b'])) {

        $this->barcode_command[8] = "C"
                                  . $this->barcode_command['8a']
                                  . $this->barcode_command['8b'];
      }

      unset($this->barcode_command['8a']);
      unset($this->barcode_command['8b']);
    }

    /* sort the barcode command elements */
    ksort($this->barcode_command);

/******************************************************************************/
/* Issue Command                                                              */
/******************************************************************************/


    $_->buffer[] = '{'
                 . array_shift($this->barcode_command) . ";"
                 . implode($this->barcode_command,',')
//                 . '=' . $data
                 . '|}';

    $_->buffer[] = '{'
                 . "RB" . sprintf("%02d", _w('root')->counters['barcode']) . ";"
                 . $data
                 . '|}';
                 
    _w('root')->counters['barcode'] ++;

  }


/******************************************************************************/
/******************************************************************************/
/* Help functions                                                           */
/******************************************************************************/

  function options_helper($configs, $source = array())
  {
    $configs = $configs[$this->family];
    $source  = explode(',', $source);

    foreach ($configs as $key => $conf) {
      $p = (isset($source[$key]) ? $source[$key] : '');

      /* Put this parameter at the end of the config array

         'type'    => 'forced',
         'value'   => '20',
         'message' => 'ECC Type : ECC200 (forced)',
         'command' => 4 */

      if($conf['type'] == 'forced'){
        $message = array('message' => $conf['message']);
        _call('utils.write_debug', $message);
        $this->barcode_command[$conf['command']] = $conf['value'];
      }


    /*  'type'    => 'range',
        'required' => FALSE,
        'default' => NULL,
        'min'     => 0,
        'max'     => 99,
        'message' => "1-cell width : ",
        'fatal'   => "BARCODE_1_CELL_WIDTH_OUT_OF_RANGE",
        'format'  => "%02d"
        'command' => 4 */

      if($conf['type'] == 'range') {
        /* no input */
        if($conf['default'] !== NULL && $p == '') {
          $d = sprintf($conf['format'], $conf['default']);
          $message = array('message' =>
            $conf['message'] . " : " . $d . " (default)");
          _call('utils.write_debug', $message);
          $this->barcode_command[$conf['command']] = $d;

        }

        /* no input no default but required nor numeric */
        else if(($p == '' && $conf['required']) ||
                ($p != '' && !is_numeric($p))) {
          $message = array('message' =>
            "FATAL : " . $conf['message'] . " not given or not a number");
          _call('utils.write_debug', $message);
          die();
        }

        /* out of range */
        else if(is_numeric($p) &&
              (intval($p) < $conf['min'] || intval($p) > $conf['max'])) {
          $message = array('message' => "FATAL : " . $conf['fatal']);
          _call('utils.write_debug', $message);
          die();
        }

        else if ($p != '') {
          $p = sprintf($conf['format'], $p);
          $message = array('message' => $conf['message'] . " : " . $p);
          _call('utils.write_debug', $message);
          $this->barcode_command[$conf['command']] = $p;
        }

        else {
          $message = array('message' => $conf['message'] . " : omitted/auto");
          _call('utils.write_debug', $message);
        }
      }

      /*  'type'     => 'options',
          'required' => TRUE,
          'default'  => 0,
          'options'  => array(
                        '0' => "0",
                        '1' => "90",
                        '2' => "180",
                        '3' => "270"),
          'message'  => "Rotational angle",
          'fatal'    => "BARCODE_WRONG_ROTATIONAL_ANGLE",
          'format'   => "%01d",
          'command'  => "7" */

      if($conf['type'] == 'options') {
        $opt = $conf['options'];
        /* no input but default */
        if($conf['default'] !== NULL && $p == '') {
          $d = sprintf($conf['format'], $conf['default']);
          $message = array('message' =>
            $conf['message'] . " : " . $opt[$conf['default']] . " (default)");
          _call('utils.write_debug', $message);
          $this->barcode_command[$conf['command']] = $d;
        }

        /* no input (no default) but required */
        else if($p == '' && $conf['required']) {
          $message = array('message' =>
            "FATAL : " . $conf['message'] . " not given");
          _call('utils.write_debug', $message);
          die();
        }

        /* not match */
        else if($opt[$p] == null && $p != '') {
          $message = array('message' => "FATAL : " . $conf['fatal']);
          _call('utils.write_debug', $message);
          die();
        }

        else if ($p != '') {
          $p = sprintf($conf['format'], $p);
          $message = array('message' => $conf['message'] . " : " . $opt[$p]);
          _call('utils.write_debug', $message);
          $this->barcode_command[$conf['command']] = $p;
        }

        else {
          $message = array('message' => $conf['message'] . " : omitted/auto");
          _call('utils.write_debug', $message);
        }
      }
    }
  }


  function calc_increment($string, $increment)
  {
    $string = str_split($string);
    $number = array_map(
      function($value){if(is_numeric($value)) return $value;},
      $string);

    $length = sprintf("%02d",count($number));
    $number = (int)implode('', $number);
    $number += $increment;
    $number = str_split(sprintf("%0". $length . "d", $number));

    $string = array_reverse($string);
    foreach($string as $key => $value){
      if(is_numeric($value)) $string[$key] = array_pop($number);
    }

    return implode('', array_reverse($string));
  }
}
?>
