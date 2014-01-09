gframework
==========

G-Framework ToolKit is a system for creating Web applications. It is not a real
framework, but a new approach to Web programming.

With it Web applications are created 'vertically' : rather than merging multiple
languages (e.g. PHP, JavaScript, and HTML) in your pages, each view is designed
in XML, and server and client code is stored in a specific place and is linked
to the view at runtime.

Everything is strongly oriented to let developers focus their efforts only to
specific aspects of the project at time : XML for the GUI, RPCs for the
data handling.

The use of XML as a 'modified' version of the simple HTML let the developers
to benefit from the following enhancements :

- Clear desining rules, avoiding CSS mess up
- Subviews rather iframes where the injected code is merged in the master view
- Possibility to create new specialized tag composed by primitives webgets.
- Internationalization dramatically simplified
- and more...

The use of RPCs let developers to completely forget the underlying Ajax 
data exchange and focus only on the their own code.

With G-Bus Web applications come to life easly with an unexpected level of
interactivity, like Google Drive where users can modify documents in realtime.

G-Framework structure is fully modular. This approach let 3rd parties to develop
new components and made them available to others developers simply as write
a TAG in an XML View.