# <center> Create Custom Module in Dolibarr </center>
<center> Specific for custom invoice template </center>
<hr>

## Summary
We will create a custom module to add some personalized tags and use it to our custom invoice 

## Step 1: Enable The Module and Application Builder

Log in to your dolibarr and go to <code>Setup->Modules/Applications</code>.<br>
At the <code>Multi-modules tools</code> section, enable the <code>Module and Application Builder</code>.

After that, you will be able to see a <code>ladybug symbol</code> at the top right corner of the window.

## Step 2: Create a module 

By clicking the <code>ladybug symbol</code>, a new window will open.
At the <code>New</code> tab, type the name you want your module to have and click <code>CREATE</code>

## Step 3: Insert code / functionality of the newly created module

	Note: add the downloaded code in the module

Now we must open the installation folder of dolibarr in our local machine or server. <br>

<code> PATH_TO_YOUR_DOLIBARR_INSTALALTION\htdocs\custom\NAME_OF_YOUR_MODULE\core </code>

Once you are there create a folder with name <code>substitutions</code> then upload the file <code>functions_invoice_subt.lib.php</code>

## Step 4: Enable the substitutions functionality 

Because we added a substitutions functionality, now we must be sure that is visible/activated by our Custom module. So, in your dolibarr click on the ladybug to open the <code>Module and Application Builder </code> and click the tab with your module name.

In order to enable the substitutions in our module we must edit the <code>Descriptor</code>
In the description tab there are listed three files<br>
-Descriptor<br>
-Readme <br>
-Changelog<br>

Click the pencil at the right side of the path to edit the file. Then search for <code>substitutions</code> and change the bellow code from

```php
 // Set this to 1 if module has its own substitution function file (core/substitutions)
            'substitutions' => 0,
```
to 

```php
 // Set this to 1 if module has its own substitution function file (core/substitutions)
            'substitutions' => 1,
```
and click save.

## Step 4: Activate the module
<strong>Bellow version 12 :</strong><br>
	At the top of the tab you can see the line bellow 
	<q> _This module is not activated yet. Go to Home-Setup-Modules/Applications to make it live or click here:_ </q> <br>
<strong>12+ version:</strong><br>
 	At the top of the tab in the center you can see a switch. Click it, in order to enable the module   



click there to activate the module.

	TIP: When you want to make changes in the Descriptor, it is wise to disable the mnodule make changes and then ativate it again. 

## Step 5: Upload the invoice template

Go to <code>Setup -> Modules/Applications</code> then invoices.<br>
At the section <code>ODT/ODS templates</code> click and upload the file Custom-Invoice.odt. <br>
<strong>DONT FORGET</strong> to enable the <code>ODT/ODS templates</code> by clicking the switch.