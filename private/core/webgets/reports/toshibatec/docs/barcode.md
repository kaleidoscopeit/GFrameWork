## reports.toshibatec.barcode
Prints a barcode among the supported ones by the Toshiba Barcode Printer

##### ACCEPTED PARAMETERS

|PARAM       |DESCRIPTION                                                      |
|------------|-----------------------------------------------------------------|
|left        |X-coordinate of the print origin of bar code                     |
|top         |Y-coordinate of the print origin of bar code                     |
|type        |Type of bar code                                                 |
|type_options|Specific options by barcode type                                 |
|style       |Specific style options by barcode type                           |
|increment   |increment step. The letters in the text will be removed and<br>  |
|            |remaining digit will be treated as an unique number to be        |
|            |incremented                                                      |
|data        |Data string to be printed                                        |
|connection  |Connection setting (for multiple 2d barcodes)                    |
---
## External references

The following text is a structured rework of the commands used to print the
barcodes as described in the tecnical manual of the Toshiba Barcode Printer.
This rework identifies the common structures in order to facilitate the build of
the framework library.

#### COMMAND STRUCTURE

| FAMILY     | COMMON                        | ASPECT                     | EXTRA                        | DATA                      |
|------------|-------------------------------|----------------------------|------------------------------|---------------------------|
|WPC         |[ESC] XBaa; bbbb, cccc, d, e,  |ff,                 k, llll | (, mnnnnnnnnnn, ooo, p, qq)  | (= sss --- sss) [LF] [NUL]|
|MSI         |[ESC] XBaa; bbbb, cccc, d, e,  |ff, gg, hh, ii, jj, k, llll | (, mnnnnnnnnnn, p, qq) (, r) | (= sss --- sss) [LF] [NUL]|
|GS1 DataBar |[ESC] XBaa; bbbb, cccc, d, e,  |ff,                 g, hhhh | (, ijjjjjjjjjj, kk) (,Sll)   | (= sss --- sss) [LF] [NUL]|
|DataMatrix  |[ESC] XBaa; bbbb, cccc, d, ee, |ff, gg,             h       | (, Ciiijjj) (, Jkkllmmmnnn)  | (= ooo --- ooo) [LF] [NUL]|
|PDF417      |[ESC] XBaa; bbbb, cccc, d, ee, |ff, gg,             h, iiii |                              | (= jjj --- jjj) [LF] [NUL]|
|MicroPDF417 |[ESC] XBaa; bbbb, cccc, d, ee, |ff, gg,             h, iiii |                              | (= jjj --- jjj) [LF] [NUL]|
|QRCode      |[ESC] XBaa; bbbb, cccc, d, e,  |ff, g,              h       | (, Mi) (, Kj) (, Jkkllmm)    | (= nnn --- nnn) [LF] [NUL]|
|MaxiCode    |[ESC] XBaa; bbbb, cccc, d  (,e)|                            | (, Jffgg) (, Zh)             | (see data     ) [LF] [NUL]|
|CPCode      |[ESC] XBaa; bbbb, cccc, d  ,e  |ff, g, h                    | (, Ciijj)                    | (= kkk --- kkk) [LF] [NUL]|


#### COMMON PROPERTIES

*Bar code number (XBaa)*

--------------------------------------------------------------------------------
*          00 to 31
<br><br>

*X-coordinate of the print origin of bar code (bbbb)(left)*

--------------------------------------------------------------------------------
*          4 or 5 digits (in 0.1 mm units)
<br><br>

*Y-coordinate of the print origin of bar code (cccc)(top)*

--------------------------------------------------------------------------------
*          4 or 5 digits (in 0.1 mm units)
<br><br>

*Type of bar code (d)(type)*

--------------------------------------------------------------------------------
|FAMILY      |TYPE                                                              |
|------------|------------------------------------------------------------------|
|WPC         |0: JAN8, EAN8                                                     |
|            |5: JAN13, EAN13                                                   |
|            |6: UPC-E                                                          |
|            |7: EAN13 + 2 digits                                               |
|            |8: EAN13 + 5 digits                                               |
|            |9: CODE128 (with auto code selection)                             |
|            |A: CODE128 (without auto code selection)                          |
|            |C: CODE93                                                         |
|            |G: UPC-E + 2 digits                                               |
|            |H: UPC-E + 5 digits                                               |
|            |I: EAN8 + 2 digits                                                |
|            |J: EAN8 + 5 digits                                                |
|            |K: UPC-A                                                          |
|            |L: UPC-A + 2 digits                                               |
|            |M: UPC-A + 5 digits                                               |
|            |N: UCC/EAN128                                                     |
|PostalWPC   |R: Customer bar code (Postal code for Japan)                      |
|            |S: Highest priority customer bar code (Postal code for Japan)     |
|            |U: POSTNET (Postal code for U.S)                                  |
|            |V: RM4SCC (ROYAL MAIL 4 STATE CUSTOMER CODE) (Postal code for U.K)|
|            |W: KIX CODE (Postal code for Belgium)                             |
|MSI         |1: MSI                                                            |
|            |2: Interleaved 2 of 5 (ITF)                                       |
|            |3: CODE39 (standard)                                              |
|            |4: NW7                                                            |
|            |B: CODE39 (full ASCII)                                            |
|            |O: Industrial 2 of 5                                              |
|            |a: MATRIX 2 of 5 for NEC                                          |
|GS1 DataBar |b: GS1 DataBar family (RSS)                                       |
|DataMatrix  |Q: Data Matrix (Two-dimensional code)                             |
|PDF417      |P: PDF417 (Two-dimensional code)                                  |
|MicroPDF417 |X: MicroPDF417 (Two-dimensional code)                             |
|QRCode      |T: QR code (Two-dimensional code)                                 |
|MaxiCode    |Z: MaxiCode (Two-dimensional code)                                |
|CPCode      |Y: CP Code (Two-dimensional code)                                 |
<br>

## *Specific options by barcode type (type_options)*

--------------------------------------------------------------------------------
List of settable parameters broken down by barcode family.
<br><br>

##### WPC

|PARAM|DESCRIPTION                                                              |
|-----|-------------------------------------------------------------------------|
|e    |[Type of check digit]                                                    |
|     |1: Without attaching check digit                                         |
|     |2: Check digit check                                                     |
|     |&nbsp;&nbsp;&nbsp;&nbsp;WPC -> Modulus 10C                               |
|     |&nbsp;&nbsp;&nbsp;&nbsp;CODE93 -> Modulus 47                             |
|     |&nbsp;&nbsp;&nbsp;&nbsp;CODE128 -> PSEUDO 103                            |
|     |3: Check digit automatic attachment (1)                                  |
|     |&nbsp;&nbsp;&nbsp;&nbsp;WPC -> Modulus 10                                |
|     |&nbsp;&nbsp;&nbsp;&nbsp;CODE93 -> Modulus 47                             |
|     |&nbsp;&nbsp;&nbsp;&nbsp;CODE128 -> PSEUDO 103                            |
|     |&nbsp;&nbsp;&nbsp;&nbsp;UCC/EAN128 -> Modulus 10 + Modulus 103           |
|     |4: Check digit automatic attachment (2)                                  |
|     |&nbsp;&nbsp;&nbsp;&nbsp;WPC -> Modulus 10 + Price C/D 4 digits           |
|     |5: Check digit automatic attachment (3)                                  |
|     |&nbsp;&nbsp;&nbsp;&nbsp;WPC -> Modulus 10 + Price C/D 5 digits           |

* For the Customer bar code, POSTNET, and RMC4SCC, only “3: Check digit auto
attachment (1)” is effective.
<br><br>

##### PostalWPC

|PARAM|DESCRIPTION                                                              |
|-----|-------------------------------------------------------------------------|
|e    |[Type of check digit]                                                    |
|     |3: Check digit automatic attachment (1)                                  |
|     |&nbsp;&nbsp;&nbsp;Customer code -> Special check digit                   |
|     |&nbsp;&nbsp;&nbsp;POSTNET -> Special check digit                         |
|     |&nbsp;&nbsp;&nbsp;RM4SCC -> Special check digit                          |
<br>

##### MSI

|PARAM|DESCRIPTION                                                              |
|-----|-------------------------------------------------------------------------|
|e    |[Type of check digit]                                                    |
|     |1: Without attaching check digit                                         |
|     |2: Check digit check                                                     |
|     |&nbsp;&nbsp;&nbsp;CODE39 -> Modulus 43                                   |
|     |&nbsp;&nbsp;&nbsp;MSI -> IBM modulus 10                                  |
|     |&nbsp;&nbsp;&nbsp;ITF -> Modulus 10                                      |
|     |&nbsp;&nbsp;&nbsp;Industrial 2 of 5 -> Modulus check character           |
|     |3: Check digit auto attachment (1)                                       |
|     |&nbsp;&nbsp;&nbsp;CODE39 -> Modulus 43                                   |
|     |&nbsp;&nbsp;&nbsp;MSI -> IBM modulus 10                                  |
|     |&nbsp;&nbsp;&nbsp;ITF -> Modulus 10                                      |
|     |&nbsp;&nbsp;&nbsp;Industrial 2 of 5 -> Modulus check character           |
|     |4: Check digit auto attachment (2)                                       |
|     |&nbsp;&nbsp;&nbsp;MSI -> IBM modulus 10 + IBM modulus 10                 |
|     |&nbsp;&nbsp;&nbsp;ITF -> DBP Modulus 10                                  |
|     |5: Check digit auto attachment (3)                                       |
|     |&nbsp;&nbsp;&nbsp;MSI -> IBM modulus 11 + IBM modulus 10                 |
|     |                                                                         |
|r    |[Designates the attachment of start/stop code]                           |
|     |&nbsp;&nbsp;&nbsp;T: Attachment of start code only                       |
|     |&nbsp;&nbsp;&nbsp;P: Attachment of stop code only                        |
|     |&nbsp;&nbsp;&nbsp;N: Start/stop code unattached                          |
<br>

##### GS1 DataBar (RSS)

|PARAM|DESCRIPTION                                                             |
|-----|------------------------------------------------------------------------|
|e    |[Version (Type of GS1 DataBar)                                          |
|     |&nbsp;&nbsp;&nbsp;1: GS1 DataBar Omnidirectional/Truncated)             |
|     |&nbsp;&nbsp;&nbsp;2: GS1 DataBar Stacked                                |
|     |&nbsp;&nbsp;&nbsp;3: GS1 DataBar Stacked Omnidirectional                |
|     |&nbsp;&nbsp;&nbsp;4: GS1 DataBar Limited                                |
|     |&nbsp;&nbsp;&nbsp;5: GS1 DataBar Expanded                               |
|     |&nbsp;&nbsp;&nbsp;6: GS1 DataBar Expanded Stacked                       |
<br>

##### DataMatrix

Data Matrix has various ECC levels and format ID possible, but last algorithm
ECC200 is the recomended for every operations and when ECC200 is selected the
Format ID is not applicated.

The library will allow to select a different ECC level than ECC200 and Format ID
but with older model cannot let to choose that values. Check the Interface
Specification manual against this.

|PARAM|DESCRIPTION                                                             |
|-----|------------------------------------------------------------------------|
|ee   |[ECC type]                                                              |
|     |&nbsp;&nbsp;&nbsp;0: ECC0                                               |
|     |&nbsp;&nbsp;&nbsp;1: ECC50                                              |
|     |&nbsp;&nbsp;&nbsp;4: ECC50                                              |
|     |&nbsp;&nbsp;&nbsp;5: ECC50                                              |
|     |&nbsp;&nbsp;&nbsp;6: ECC80                                              |
|     |&nbsp;&nbsp;&nbsp;7: ECC80                                              |
|     |&nbsp;&nbsp;&nbsp;8: ECC80                                              |
|     |&nbsp;&nbsp;&nbsp;9: ECC100                                             |
|     |&nbsp;&nbsp;&nbsp;10: ECC100                                            |
|     |&nbsp;&nbsp;&nbsp;11: ECC140                                            |
|     |&nbsp;&nbsp;&nbsp;12: ECC140                                            |
|     |&nbsp;&nbsp;&nbsp;13: ECC140                                            |
|     |&nbsp;&nbsp;&nbsp;14: ECC140                                            |
|     |&nbsp;&nbsp;&nbsp;20: ECC200                                            |
|     |                                                                        |
|gg   |[Format ID]                                                             |
|     |&nbsp;&nbsp;&nbsp;1: Format ID 1                                        |
|     |&nbsp;&nbsp;&nbsp;2: Format ID 2                                        |
|     |&nbsp;&nbsp;&nbsp;3: Format ID 3                                        |
|     |&nbsp;&nbsp;&nbsp;4: Format ID 4                                        |
|     |&nbsp;&nbsp;&nbsp;5: Format ID 5                                        |
|     |&nbsp;&nbsp;&nbsp;6: Format ID 6                                        |
<br>

##### PDF417

|PARAM|DESCRIPTION       |
|-----|:----------------:|
|ee   | [Security level] |
|     |    00:Level 0    |
|     |       to         |
|     |    08:Level 8    |
<br>

##### Micro PDF417

Micro PDF417 supports only "Fixed" as security level then the library will not
accept any parameter.

Following table is kept only as a reference.

|PARAM|   DESCRIPTION    |
|-----|:----------------:|
|ee   | [Security level] |
|     |    00: Fixed     |
<br>

##### QRCode

|PARAM|DESCRIPTION                                                             |
|-----|------------------------------------------------------------------------|
|e    |[Error correction level]                                                |
|     |L: High density level,                                                  |
|     |M: Standard level,                                                      |
|     |Q: Reliability level,                                                   |
|     |H: High reliability level                                               |
|     |                                                                        |
|g    |[Selection of mode]                                                     |
|     |M: Manual mode,                                                         |
|     |A: Automatic mode                                                       |
|     |                                                                        |
|Mi   |[Selection of model]                                                    |
|     |*Omissible. When omitted, Model 1 is automatically selected.*           |
|     |1: Model 1                                                              |
|     |2: Model 2                                                              |
|     |                                                                        |
|Kj   |[Mask number]                                                           |
|     |*Omissible. When omitted, the number is automatically set.)*            |
|     |0-7: Mask number 0 to 7                                                 |
|     |8: No mask                                                              |
<br>

##### MaxiCode

|PARAM|DESCRIPTION                                                             |
|-----|------------------------------------------------------------------------|
|e    |[Mode selection]                                                        |
|     |*Omissible (when omitted : Mode 2)*                                     |
|     |0: Mode 0 (Old specification)                                           |
|     |1: Mode 1 (Old specification)                                           |
|     |2: Mode 2 (New specification)                                           |
|     |3: Mode 3 (New specification)                                           |
|     |4: Mode 4 (New specification)                                           |
|     |5: Mode 2 (New specification)                                           |
|     |6: Mode 6 (New specification)                                           |
|     |7: Mode 2 (New specification)                                           |
|     |8: Mode 2 (New specification)                                           |
|     |9: Mode 2 (New specification)                                           |
<br>

##### CPCode

Designation of ECC (Error Correction Code) level

Only when the number of code characters are specified, “0” (No designation)
can be selected. If “0 (No designation)” is selected without specifying the
number of code characters, the CP code is not printed. When the number
of code characters are specified, blank code areas created after the
characters are encoded should all be filled with ECC characters.

|PARAM|DESCRIPTION                                                             |
|-----|------------------------------------------------------------------------|
|ee   |[Designation of ECC (Error Correction Code) level]                      |
|     |&nbsp;&nbsp;&nbsp;0: No designation                                     |
|     |&nbsp;&nbsp;&nbsp;1: 10%                                                |
|     |&nbsp;&nbsp;&nbsp;2: 20%                                                |
|     |&nbsp;&nbsp;&nbsp;3: 30%                                                |
|     |&nbsp;&nbsp;&nbsp;4: 40%                                                |
|     |&nbsp;&nbsp;&nbsp;5: 50%                                                |
<br>

## *Specific options by barcode type (style)*

--------------------------------------------------------------------------------
List of styles broken down by barcode family.
<br><br>

##### WPC

|PARAM|DESCRIPTION                |VALUES                                      |
|-----|---------------------------|--------------------------------------------|
|ff   |[1-module width]           |01 to 15 (in units of dots)                 |
|     |                           |                                            |
|llll |[Height of the bar code]   |0000 to 1000 (in 0.1 mm units)              |
|     |                           |                                            |
|ooo  |[Length of WPC guard bar]  |000 to 100 (in 0.1 mm units)                |
|     |                           |*When omitted, the guard bar is not*        |
|     |                           |*extended.*                                 |
|     |                           |                                            |
|p    |[numerals under bars]      |0: Non-print - 1: Print                     |
|     |                           |*When omitted, the numerals under the bars* |
|     |                           |*are not printed.*                          |
|     |                           |                                            |
|k    |[Rotational angle]         |0,90,180,270                                |
<br>

##### PostalWPC

|PARAM|DESCRIPTION                |VALUES                                      |
|-----|---------------------------|--------------------------------------------|
|ff   |[1-module width]           |01 to 15 (in units of dots)                 |
|     |                           |                                            |
|llll |[Height of the bar code]   |0000 to 1000 (in 0.1 mm units)              |
|     |                           |                                            |
|k    |[Rotational angle]         |0,90,180,270                                |
<br>

##### GS1 DataBar

|PARAM|DESCRIPTION                |VALUES                                      |
|-----|---------------------------|--------------------------------------------|
|ff   |[1-module width]           |01 to 15 (in units of dots)                 |
|     |                           |                                            |
|hhhh |[Height of the bar code]   |0000 to 1000 (in 0.1 mm units)              |
|     |                           |                                            |
|Sll  |[Segment width]            |not yet supported                           |
|     |                           |                                            |
|g    |[Rotational angle]         |0,90,180,270                                |


<br><br>

##### MSI

|PARAM|DESCRIPTION                |VALUES                                      |
|-----|---------------------------|--------------------------------------------|
|ff   |[Narrow bar width]         |01 to 99 units of dots                      |
|     |                           |                                            |
|gg   |[Narrow space width]       |01 to 99 units of dots                      |
|     |                           |*In the case of industrial 2 of 5,*         |
|     |                           |*designate an element-to-element space.*    |
|     |                           |                                            |
|hh   |[Wide bar width]           |01 to 99 units of dots                      |
|     |                           |                                            |
|ii   |[Wide space width]         |01 to 99 units of dots                      |
|     |                           |*In the case of industrial 2 of 5,*         |
|     |                           |*the value is fixed to 00.*                 |
|     |                           |                                            |
|jj   |[Char-to-char space width] |01 to 99 units of dots                      |
|     |                           |*In the case of MSI and ITF, char-to-char*  |
|     |                           |*space width is fixed to 00*                |
|     |                           |                                            |
|llll |[Height of the bar code]   |0000 to 1000 (in 0.1 mm units)              |
|     |                           |                                            |
|p    |[numerals under bars]      |0: Non-print - 1: Print                     |
|     |                           |*Omissible. When omitted, the numerals*     |
|     |                           |*under the bars are not printed.*           |
|     |                           |                                            |
|k    |[Rotational angle]         |0,90,180,270                                |
<br>

##### DataMatrix

|PARAM|DESCRIPTION                |VALUES                                      |
|-----|---------------------------|--------------------------------------------|
|ff   |[1-cell width]             |00 to 99 (in units of dots)                 |
|     |                           |                                            |
|iii  |[No. of cells in X dir]    |000 to 144 (Omissible)                      |
|jjj  |[No. of cells in Y dir ]   |000 to 144 (Omissible)                      |
|     |                           |*Both are part of Ciiijjj param in the*     |
|     |                           |*labeler command.When one of the two is*    |
|     |                           |*omitted, the other is ignored and values*  |
|     |                           |*are automatically set.*                    |
|     |                           |                                            |
|h    |[Rotational angle]         |0,90,180,270                                |
<br>

##### PDF417 & Micro PDF417

|PARAM|DESCRIPTION                |VALUES                                      |
|-----|---------------------------|--------------------------------------------|
|ff   |[1-module width]           |01 to 10 (in units of dots)                 |
|     |                           |                                            |
|gg   |[No. of columns (strings)] |01 to PDF417 : 30 - Micro PDF417 : 38       |
|     |                           |                                            |
|iiii |[Bar height]               |0000 to 0100 (in 0.1 mm units)              |
|     |                           |                                            |
|h    |[Rotational angle]         |0,90,180,270                                |
<br>

##### QRCode

|PARAM|DESCRIPTION                |VALUES                                      |
|-----|---------------------------|--------------------------------------------|
|ff   |[1-cell width]             |00 to 52 (in units of dots)                 |
|     |                           |                                            |
|h    |[Rotational angle]         |0,90,180,270                                |
<br>

##### MaxiCode

|PARAM|DESCRIPTION                                                             |
|-----|------------------------------------------------------------------------|
|Zh   |[Attachment of Zipper block and Contrast block]                         |
|     |*Omissible. When omitted, they are not attached.*                       |
|     |0: No attachment of Zipper block and Contrast block                     |
|     |1: Attachment of Zipper block and Contrast block                        |
|     |2: Attachment of Zipper block                                           |
|     |3: Attachment of Contrast block                                         |
<br>

##### CPCode

|PARAM|DESCRIPTION                |VALUES                                      |
|-----|---------------------------|--------------------------------------------|
|ff   |[1-cell width]             |01 to 99 (in units of dots)                 |
|     |                           |                                            |
|g    |[No. of character bits]    |0: Automatically set - A: 8 bits            |
|     |                           |                                            |
|ii   |[No. of code char in X dir]|03 to 22 (Omissible)                        |
|jj   |[No. of code char in Y dir]|02 to 22 (Omissible)                        |
|     |                           |*Both are part of Ciijj param in the*       |
|     |                           |*labeler command.When one of the two is*    |
|     |                           |*omitted, the other is ignored and values*  |
|     |                           |*are automatically set.*                    |
|     |                           |                                            |
|h    |[Rotational angle]         |0,90,180,270                                |
<br>

## *Enumeration*

--------------------------------------------------------------------------------
Command to determine the enumeration (where applicable).
<br><br>

##### WPC & MSI (mnnnnnnnnnn,qq)

|PARAM     |DESCRIPTION                                                        |
|----------|-------------------------------------------------------------------|
|m         |[Indicates whether to increment or decrement]                      |
|          |*Omissible. When omitted, incr/decr is not performed.*             |
|          |+: Increment, −: Decrement                                         |
|          |                                                                   |
|nnnnnnnnnn|[Skip value]                                                       |
|          |0000000000 to 9999999999                                           |
|          |                                                                   |
|qq        |[No. of digits after zero suppression]                             |
|          |*Omissible. When omitted, zero suppression is not performed.*      |
|          |00 to 20                                                           |      
<br>

##### GS1 Databar (ijjjjjjjjjj,kk)

|PARAM     |DESCRIPTION                                                        |
|----------|-------------------------------------------------------------------|
|i         |[Indicates whether to increment or decrement]                      |
|          |*Omissible. When omitted, incr/decr is not performed.*             |
|          |+: Increment, −: Decrement                                         |
|          |                                                                   |
|jjjjjjjjjj|[Skip value]                                                       |
|          |0000000000 to 9999999999                                           |
|          |                                                                   |
|kk        |[No. of digits after zero suppression]                             |
|          |*Omissible. When omitted, zero suppression is not performed.*      |
|          |00 to 20                                                           |
<br>

## *Data string (data)*

--------------------------------------------------------------------------------
data string to be printed as content of the barcode
<br><br>

#### Families with common behaviour

|FAMILY      |VALUE                                                               |
|------------|--------------------------------------------------------------------|
|WPC         |sss ----- sss (Omissible)                                           |
|            |*Max. 126 digits. However, it varies dep. on the type of bar code.* |
|            |                                                                    |
|MSI         |sss ----- sss (Omissible)                                           |
|            |*Max. 126 digits. However, it varies dep. on the type of bar code.* |
|            |                                                                    |
|GS1 DataBar |sss ----- sss (Omissible)                                           |
|            |*Max. 126 digits. However, it varies dep. on the type of bar code.* |
|            |                                                                    |
|DataMatrix  |ooo ----- ooo (Omissible)                                           |
|            |*Max. 2000 digits. However, it varies dep. on the type of bar code.*|
|            |                                                                    |
|PDF417      |jjj ----- jjj (Omissible)                                           |
|            |*Max. 2000 digits. However, it varies dep. on the type of bar code.*|
|            |                                                                    |
|Micro PDF417|jjj ----- jjj (Omissible)                                           |
|            |*Max. 366 digits. However, it varies dep. on the type of bar code.* |
|            |                                                                    |
|QRCode      |nnn ----- nnn (Omissible)                                           |
|            |*Max. 2000 digits. However, it varies dep. on the type of bar code.*|
<br>

#### MaxiCode
MaxiCode needs to send the data command separately, an it depends by the type.

*For Mode 2 ([ESC] RBaa; bbbbbbbbbcccdddeee --- eee [LF] [NUL])*

|PARAM     | DESCRIPTION          |VALUE                                       |
|----------|----------------------|--------------------------------------------|
|bbbbb     | Zip code             | Fixed as 5 digits (Numerics)               |
|bbbb      | Zip code extension   | Fixed as 4 digits (Numerics)               |
|ccc       | Class of service     | Fixed as 3 digits (Numerics)               |
|ddd       | Country code         | Fixed as 3 digits (Numerics)               |
|eee---eee | Message data strings | 84 digits                                  |
<br>

*For Mode 3 ([ESC] RBaa; bbbbbbbbbcccdddeee --- eee [LF] [NUL])*

|PARAM     | DESCRIPTION          |VALUE                                       |
|----------|----------------------|--------------------------------------------|
|bbbbbb    | Zip code             | Fixed as 5 digits (Character of code set A)|
|bbb       | Vacant               | Fixed as 3 digits (20H)                    |
|ccc       | Class of service     | Fixed as 3 digits (Numerics)               |
|ddd       | Country code         | Fixed as 3 digits (Numerics)               |
|eee---eee | Message data strings | 84 digits                                  |
<br>

*For mode 4 or 6 ([ESC] RBaa; fffffffffggg --- ggg [LF] [NUL])*

|PARAM     | DESCRIPTION                    |VALUE                             |
|----------|--------------------------------|----------------------------------|
|fffffffff | Primary message data strings   | 9 digits                         |
|ggg---ggg | Secondary message data strings | 84 digits                        |
<br>

## *Connection setting (connection)*

--------------------------------------------------------------------------------
This parameter determine how join multiple 2d barcodes. It is applicable only to
certain 2d barcodes.
<br><br>

##### DataMatrix (Jkkllmmmnnn)
*Omissible. When omitted, connection is not made.*

|PARAM     | DESCRIPTION                    |VALUE                             |
|----------|--------------------------------|----------------------------------|
|kk        | Code number                    | 01 to 16                         |
|          |                                |                                  |
|ll        | Number of divided codes        | 02 to 16                         |
|          |                                |                                  |
|mmm       | ID number 1                    | 001 to 254                       |
|          |                                |                                  |
|nnn       | ID number 2                    | 001 to 254                       |
<br>

##### QRCode (Jkkllmm)
*Omissible. When omitted, connection is not made.*

|PARAM     | DESCRIPTION                                         |VALUE        |
|----------|-----------------------------------------------------|-------------|
|          |                                                     |             |
|kk        | Value indicating which divided code is connected    | 01 to 16    |
|          |                                                     |             |
|ll        | Number of divided codes                             | 01 to 16    |
|          |                                                     |             |
|mm        | A value for all print data (before devided) which   |             |
|          | have been XORed in units of bytes.                  | 01 to FF    |
<br>

##### MaxiCode (Jffgg)
*Omissible. When omitted, connection is not made.*

|PARAM     | DESCRIPTION                    |VALUE                             |
|----------|--------------------------------|----------------------------------|
|ff        | Code number                    | 01 to 08                         |
|          |                                |                                  |
|gg        | Number of divided codes        | 01 to 08                         |
