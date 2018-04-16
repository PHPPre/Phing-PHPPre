# Phing-PHPPre
PHPPre is a preprocessor operating on PHP source code before deployment. It was written as internal task of Phing.
## Features
Preprocessor can be used to:
* conditional insertion of blocks of code
* conditional source file inclusion
* informing about errors and warnings in configuration
* setting configuration

Currently supports directives:

* Control:
    * &#35;if *SIMPLE_EXPRESSION*
    * &#35;ifdef *DEFINITION*
    * &#35;ifndef *DEFINITION*
    * &#35;else
    * &#35;endif
* Message:
    * &#35;error
    * &#35;message
    * &#35;warning

Work in progress:

Future releases:

* Control:
    * &#35;elif *SIMPLE_EXPRESSION*
    * &#35;elifdef *DEFINITION*
    * &#35;elifndef *DEFINITION*
* Definition:
    * &#35;define
    * &#35;undef
* Includes:
    * &#35;include *"filename.php"*
    * &#35;include *&lt;filename.php&gt;*
    
## Usage
```xml
<target name="preprocessing">
    <phppre>
        <fileset dir="../project/">
            <include name="**/*.php" />
        </fileset>
     </phppre>
</target>
```

## Documentation

Available at github repository wiki.

## Licensing

This software is licensed under GNU LGPL v3. You may find the terms in the "LICENSE" file in this directory.
