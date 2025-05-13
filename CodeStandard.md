# PHP_CodeSniffer Coding Standard

## Array Indent

The opening brace of a multi-line array must be indented at least to the same level as the start of the statement.
  <table>
   <tr>
    <th>Valid: Opening brace of a multi-line array indented to the same level as the start of the statement.</th>
    <th>Invalid: Opening brace of a multi-line array not indented to the same level as the start of the statement.</th>
   </tr>
   <tr>
<td>

    $b = [
        1,
        2,
    ];
    
    if ($condition) {
        $a =
        [
            1,
            2,
        ];
    }

</td>
<td>

    if ($condition) {
        $a =
    [
            1,
            2,
        ];
    }

</td>
   </tr>
  </table>
Each array element must be indented exactly four spaces from the start of the statement.
  <table>
   <tr>
    <th>Valid: Each array element is indented by exactly four spaces.</th>
    <th>Invalid: Array elements not indented by four spaces.</th>
   </tr>
   <tr>
<td>

    $a = array(
        1,
        2,
        3,
    );

</td>
<td>

    $a = array(
      1,
         2,
            3,
    );

</td>
   </tr>
  </table>
The array closing brace must be on a new line.
  <table>
   <tr>
    <th>Valid: Array closing brace on its own line.</th>
    <th>Invalid: Array closing brace not on its own line.</th>
   </tr>
   <tr>
<td>

    $a = [
        1,
        2,
    ];

</td>
<td>

    $a = [
        1,
        2,];

</td>
   </tr>
  </table>
The closing brace must be aligned with the start of the statement containing the array opener.
  <table>
   <tr>
    <th>Valid: Closing brace aligned with the start of the statement containing the array opener.</th>
    <th>Invalid: Closing brace not aligned with the start of the statement containing the array opener.</th>
   </tr>
   <tr>
<td>

    $a = array(
        1,
        2,
    );

</td>
<td>

    $a = array(
        1,
        2,
      );

</td>
   </tr>
  </table>

## Short Array Syntax

Short array syntax must be used to define arrays.
  <table>
   <tr>
    <th>Valid: Short form of array.</th>
    <th>Invalid: Long form of array.</th>
   </tr>
   <tr>
<td>

    $arr = [
        'foo' => 'bar',
    ];

</td>
<td>

    $arr = array(
        'foo' => 'bar',
    );

</td>
   </tr>
  </table>

## Long Array Syntax

Long array syntax must be used to define arrays.
  <table>
   <tr>
    <th>Valid: Long form of array.</th>
    <th>Invalid: Short form of array.</th>
   </tr>
   <tr>
<td>

    $arr = array(
        'foo' => 'bar',
    );

</td>
<td>

    $arr = [
        'foo' => 'bar',
    ];

</td>
   </tr>
  </table>

## Duplicate Class Names

Class and Interface names should be unique in a project.  They should never be duplicated.
  <table>
   <tr>
    <th>Valid: A unique class name.</th>
    <th>Invalid: A class duplicated (including across multiple files).</th>
   </tr>
   <tr>
<td>

    class Foo
    {
    }

</td>
<td>

    class Foo
    {
    }
    
    class Foo
    {
    }

</td>
   </tr>
  </table>

## Opening Brace on Same Line

The opening brace of a class must be on the same line after the definition and must be the last thing on that line.
  <table>
   <tr>
    <th>Valid: Opening brace on the same line.</th>
    <th>Invalid: Opening brace on the next line.</th>
   </tr>
   <tr>
<td>

    class Foo {
    }

</td>
<td>

    class Foo
    {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Opening brace is the last thing on the line.</th>
    <th>Invalid: Opening brace not last thing on the line.</th>
   </tr>
   <tr>
<td>

    class Foo {
    }

</td>
<td>

    class Foo { // Start of class.
    }

</td>
   </tr>
  </table>

## Assignment In Condition

Variable assignments should not be made within conditions.
  <table>
   <tr>
    <th>Valid: A variable comparison being executed within a condition.</th>
    <th>Invalid: A variable assignment being made within a condition.</th>
   </tr>
   <tr>
<td>

    if ($test === 'abc') {
        // Code.
    }

</td>
<td>

    if ($test = 'abc') {
        // Code.
    }

</td>
   </tr>
  </table>

## Empty PHP Statement

Empty PHP tags are not allowed.
  <table>
   <tr>
    <th>Valid: There is at least one statement inside the PHP tag pair.</th>
    <th>Invalid: There is no statement inside the PHP tag pair.</th>
   </tr>
   <tr>
<td>

    <?php echo 'Hello World'; ?>
    <?= 'Hello World'; ?>

</td>
<td>

    <?php ; ?>
    <?=  ?>

</td>
   </tr>
  </table>
Superfluous semicolons are not allowed.
  <table>
   <tr>
    <th>Valid: There is no superfluous semicolon after a PHP statement.</th>
    <th>Invalid: There are one or more superfluous semicolons after a PHP statement.</th>
   </tr>
   <tr>
<td>

    function_call();
    if (true) {
        echo 'Hello World';
    }

</td>
<td>

    function_call();;;
    if (true) {
        echo 'Hello World';
    };

</td>
   </tr>
  </table>

## Empty Statements

Control Structures must have at least one statement inside of the body.
  <table>
   <tr>
    <th>Valid: There is a statement inside the control structure.</th>
    <th>Invalid: The control structure has no statements.</th>
   </tr>
   <tr>
<td>

    if ($test) {
        $var = 1;
    }

</td>
<td>

    if ($test) {
        // do nothing
    }

</td>
   </tr>
  </table>

## Condition-Only For Loops

For loops that have only a second expression (the condition) should be converted to while loops.
  <table>
   <tr>
    <th>Valid: A for loop is used with all three expressions.</th>
    <th>Invalid: A for loop is used without a first or third expression.</th>
   </tr>
   <tr>
<td>

    for ($i = 0; $i < 10; $i++) {
        echo "{$i}\n";
    }

</td>
<td>

    for (;$test;) {
        $test = doSomething();
    }

</td>
   </tr>
  </table>

## For Loops With Function Calls in the Test

For loops should not call functions inside the test for the loop when they can be computed beforehand.
  <table>
   <tr>
    <th>Valid: A for loop that determines its end condition before the loop starts.</th>
    <th>Invalid: A for loop that unnecessarily computes the same value on every iteration.</th>
   </tr>
   <tr>
<td>

    $end = count($foo);
    for ($i = 0; $i < $end; $i++) {
        echo $foo[$i]."\n";
    }

</td>
<td>

    for ($i = 0; $i < count($foo); $i++) {
        echo $foo[$i]."\n";
    }

</td>
   </tr>
  </table>

## Jumbled Incrementers

Incrementers in nested loops should use different variable names.
  <table>
   <tr>
    <th>Valid: Two different variables being used to increment.</th>
    <th>Invalid: Inner incrementer is the same variable name as the outer one.</th>
   </tr>
   <tr>
<td>

    for ($i = 0; $i < 10; $i++) {
        for ($j = 0; $j < 10; $j++) {
        }
    }

</td>
<td>

    for ($i = 0; $i < 10; $i++) {
        for ($j = 0; $j < 10; $i++) {
        }
    }

</td>
   </tr>
  </table>

## Require Explicit Boolean Operator Precedence

Forbids mixing different binary boolean operators (&amp;&amp;, ||, and, or, xor) within a single expression without making precedence clear using parentheses.
  <table>
   <tr>
    <th>Valid: Making precedence clear with parentheses.</th>
    <th>Invalid: Not using parentheses.</th>
   </tr>
   <tr>
<td>

    $one = false;
    $two = false;
    $three = true;
    
    $result = ($one && $two) || $three;
    $result2 = $one && ($two || $three);
    $result3 = ($one && !$two) xor $three;
    $result4 = $one && (!$two xor $three);
    
    if (
        ($result && !$result3)
        || (!$result && $result3)
    ) {}

</td>
<td>

    $one = false;
    $two = false;
    $three = true;
    
    $result = $one && $two || $three;
    
    $result3 = $one && !$two xor $three;
    
    
    if (
        $result && !$result3
        || !$result && $result3
    ) {}

</td>
   </tr>
  </table>

## Unconditional If Statements

If statements that are always evaluated should not be used.
  <table>
   <tr>
    <th>Valid: An if statement that only executes conditionally.</th>
    <th>Invalid: An if statement that is always performed.</th>
   </tr>
   <tr>
<td>

    if ($test) {
        $var = 1;
    }

</td>
<td>

    if (true) {
        $var = 1;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: An if statement that only executes conditionally.</th>
    <th>Invalid: An if statement that is never performed.</th>
   </tr>
   <tr>
<td>

    if ($test) {
        $var = 1;
    }

</td>
<td>

    if (false) {
        $var = 1;
    }

</td>
   </tr>
  </table>

## Unnecessary Final Modifiers

Methods should not be declared final inside of classes that are declared final.
  <table>
   <tr>
    <th>Valid: A method in a final class is not marked final.</th>
    <th>Invalid: A method in a final class is also marked final.</th>
   </tr>
   <tr>
<td>

    final class Foo
    {
        public function bar()
        {
        }
    }

</td>
<td>

    final class Foo
    {
        public final function bar()
        {
        }
    }

</td>
   </tr>
  </table>

## Unused function parameters

All parameters in a functions signature should be used within the function.
  <table>
   <tr>
    <th>Valid: All the parameters are used.</th>
    <th>Invalid: One of the parameters is not being used.</th>
   </tr>
   <tr>
<td>

    function addThree($a, $b, $c)
    {
        return $a + $b + $c;
    }

</td>
<td>

    function addThree($a, $b, $c)
    {
        return $a + $b;
    }

</td>
   </tr>
  </table>

## Useless Overriding Method

It is discouraged to override a method if the overriding method only calls the parent method.
  <table>
   <tr>
    <th>Valid: A method that extends functionality of a parent method.</th>
    <th>Invalid: An overriding method that only calls the parent method.</th>
   </tr>
   <tr>
<td>

    final class Foo extends Baz
    {
        public function bar()
        {
            parent::bar();
            $this->doSomethingElse();
        }
    }

</td>
<td>

    final class Foo extends Baz
    {
        public function bar()
        {
            parent::bar();
        }
    }

</td>
   </tr>
  </table>

## Doc Comment

Enforces rules related to the formatting of DocBlocks (&quot;Doc Comments&quot;) in PHP code.

DocBlocks are a special type of comment that can provide information about a structural element. In the context of DocBlocks, the following are considered structural elements:  
class, interface, trait, enum, function, property, constant, variable declarations and require/include[_once] statements.

DocBlocks start with a `/**` marker and end on `*/`. This sniff will check the formatting of all DocBlocks, independently of whether or not they are attached to a structural element.
A DocBlock must not be empty.
  <table>
   <tr>
    <th>Valid: DocBlock with some content.</th>
    <th>Invalid: Empty DocBlock.</th>
   </tr>
   <tr>
<td>

    /**
     * Some content.
     */

</td>
<td>

    /**
     * 
     */

</td>
   </tr>
  </table>
The opening and closing DocBlock tags must be the only content on the line.
  <table>
   <tr>
    <th>Valid: The opening and closing DocBlock tags have to be on a line by themselves.</th>
    <th>Invalid: The opening and closing DocBlock tags are not on a line by themselves.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description.
     */

</td>
<td>

    /** Short description. */

</td>
   </tr>
  </table>
The DocBlock must have a short description, and it must be on the first line.
  <table>
   <tr>
    <th>Valid: DocBlock with a short description on the first line.</th>
    <th>Invalid: DocBlock without a short description or short description not on the first line.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description.
     */

</td>
<td>

    /**
     * @return int
     */
    
    /**
     *
     * Short description.
     */

</td>
   </tr>
  </table>
Both the short description, as well as the long description, must start with a capital letter.
  <table>
   <tr>
    <th>Valid: Both the short and long description start with a capital letter.</th>
    <th>Invalid: Neither short nor long description starts with a capital letter.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description.
     *
     * Long description.
     */

</td>
<td>

    /**
     * short description.
     *
     * long description.
     */

</td>
   </tr>
  </table>
There must be exactly one blank line separating the short description, the long description and tag groups.
  <table>
   <tr>
    <th>Valid: One blank line separating the short description, the long description and tag groups.</th>
    <th>Invalid: More than one or no blank line separating the short description, the long description and tag groups.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description.
     *
     * Long description.
     *
     * @param int $foo
     */

</td>
<td>

    /**
     * Short description.
     *
     *
    
     * Long description.
     * @param int $foo
     */

</td>
   </tr>
  </table>
Parameter tags must be grouped together.
  <table>
   <tr>
    <th>Valid: Parameter tags grouped together.</th>
    <th>Invalid: Parameter tags not grouped together.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description.
     *
     * @param int $foo
     * @param string $bar
     */

</td>
<td>

    /**
     * Short description.
     *
     * @param int $foo
     *
     * @param string $bar
     */

</td>
   </tr>
  </table>
Parameter tags must not be grouped together with other tags.
  <table>
   <tr>
    <th>Valid: Parameter tags are not grouped together with other tags.</th>
    <th>Invalid: Parameter tags grouped together with other tags.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description.
     *
     * @param int $foo
     *
     * @since      3.4.8
     * @deprecated 6.0.0
     */

</td>
<td>

    /**
     * Short description.
     *
     * @param      int $foo
     * @since      3.4.8
     * @deprecated 6.0.0
     */

</td>
   </tr>
  </table>
Tag values for different tags in the same group must be aligned with each other.
  <table>
   <tr>
    <th>Valid: Tag values for different tags in the same tag group are aligned with each other.</th>
    <th>Invalid: Tag values for different tags in the same tag group are not aligned with each other.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description.
     *
     * @since      0.5.0
     * @deprecated 1.0.0
     */

</td>
<td>

    /**
     * Short description.
     *
     * @since 0.5.0
     * @deprecated 1.0.0
     */

</td>
   </tr>
  </table>
Parameter tags must be defined before other tags in a DocBlock.
  <table>
   <tr>
    <th>Valid: Parameter tags are defined first.</th>
    <th>Invalid: Parameter tags are not defined first.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description.
     *
     * @param string $foo
     *
     * @return void
     */

</td>
<td>

    /**
     * Short description.
     *
     * @return void
     *
     * @param string $bar
     */

</td>
   </tr>
  </table>
There must be no additional blank (comment) lines before the closing DocBlock tag.
  <table>
   <tr>
    <th>Valid: No additional blank lines before the closing DocBlock tag.</th>
    <th>Invalid: Additional blank lines before the closing DocBlock tag.</th>
   </tr>
   <tr>
<td>

    /**
     * Short description.
     */

</td>
<td>

    /**
     * Short description.
     *
     */

</td>
   </tr>
  </table>

## Fixme Comments

FIXME Statements should be taken care of.
  <table>
   <tr>
    <th>Valid: A comment without a fixme.</th>
    <th>Invalid: A fixme comment.</th>
   </tr>
   <tr>
<td>

    // Handle strange case
    if ($test) {
        $var = 1;
    }

</td>
<td>

    // FIXME: This needs to be fixed!
    if ($test) {
        $var = 1;
    }

</td>
   </tr>
  </table>

## Todo Comments

TODO Statements should be taken care of.
  <table>
   <tr>
    <th>Valid: A comment without a todo.</th>
    <th>Invalid: A todo comment.</th>
   </tr>
   <tr>
<td>

    // Handle strange case
    if ($test) {
        $var = 1;
    }

</td>
<td>

    // TODO: This needs to be fixed!
    if ($test) {
        $var = 1;
    }

</td>
   </tr>
  </table>

## Disallow Yoda conditions

Yoda conditions are disallowed.
  <table>
   <tr>
    <th>Valid: Value to be asserted must go on the right side of the comparison.</th>
    <th>Invalid: Value to be asserted must not be on the left.</th>
   </tr>
   <tr>
<td>

    if ($test === null) {
        $var = 1;
    }

</td>
<td>

    if (null === $test) {
        $var = 1;
    }

</td>
   </tr>
  </table>

## Inline Control Structures

Control Structures should use braces.
  <table>
   <tr>
    <th>Valid: Braces are used around the control structure.</th>
    <th>Invalid: No braces are used for the control structure..</th>
   </tr>
   <tr>
<td>

    if ($test) {
        $var = 1;
    }

</td>
<td>

    if ($test)
        $var = 1;

</td>
   </tr>
  </table>

## Closure Linter

All javascript files should pass basic Closure Linter tests.
  <table>
   <tr>
    <th>Valid: Valid JS Syntax is used.</th>
    <th>Invalid: Trailing comma in a javascript array.</th>
   </tr>
   <tr>
<td>

    var foo = [1, 2];

</td>
<td>

    var foo = [1, 2,];

</td>
   </tr>
  </table>

## CSSLint

All css files should pass the basic csslint tests.
  <table>
   <tr>
    <th>Valid: Valid CSS Syntax is used.</th>
    <th>Invalid: The CSS has a typo in it.</th>
   </tr>
   <tr>
<td>

    .foo: { width: 100%; }

</td>
<td>

    .foo: { width: 100 %; }

</td>
   </tr>
  </table>

## JSHint

All javascript files should pass basic JSHint tests.
  <table>
   <tr>
    <th>Valid: Valid JS Syntax is used.</th>
    <th>Invalid: The Javascript is using an undefined variable.</th>
   </tr>
   <tr>
<td>

    var foo = 5;

</td>
<td>

    foo = 5;

</td>
   </tr>
  </table>

## Byte Order Marks

Byte Order Marks that may corrupt your application should not be used.  These include 0xefbbbf (UTF-8), 0xfeff (UTF-16 BE) and 0xfffe (UTF-16 LE).

## End of File Newline

Files should end with a newline character.

## No End of File Newline

Files should not end with a newline character.

## Executable Files

Files should not be executable.

## Inline HTML

Files that contain PHP code should only have PHP code and should not have any &quot;inline html&quot;.
  <table>
   <tr>
    <th>Valid: A PHP file with only PHP code in it.</th>
    <th>Invalid: A PHP file with html in it outside of the PHP tags.</th>
   </tr>
   <tr>
<td>

    <?php
    $foo = 'bar';
    echo $foo . 'baz';

</td>
<td>

    some string here
    <?php
    $foo = 'bar';
    echo $foo . 'baz';

</td>
   </tr>
  </table>

## Line Endings

Unix-style line endings are preferred (&quot;\n&quot; instead of &quot;\r\n&quot;).

## Lowercased Filenames

Lowercase filenames are required.

## One Class Per File

There should only be one class defined in a file.
  <table>
   <tr>
    <th>Valid: Only one class in the file.</th>
    <th>Invalid: Multiple classes defined in one file.</th>
   </tr>
   <tr>
<td>

    <?php
    class Foo
    {
    }

</td>
<td>

    <?php
    class Foo
    {
    }
    
    class Bar
    {
    }

</td>
   </tr>
  </table>

## One Interface Per File

There should only be one interface defined in a file.
  <table>
   <tr>
    <th>Valid: Only one interface in the file.</th>
    <th>Invalid: Multiple interfaces defined in one file.</th>
   </tr>
   <tr>
<td>

    <?php
    interface Foo
    {
    }

</td>
<td>

    <?php
    interface Foo
    {
    }
    
    interface Bar
    {
    }

</td>
   </tr>
  </table>

## One Object Structure Per File

There should only be one class or interface or trait defined in a file.
  <table>
   <tr>
    <th>Valid: Only one object structure in the file.</th>
    <th>Invalid: Multiple object structures defined in one file.</th>
   </tr>
   <tr>
<td>

    <?php
    trait Foo
    {
    }

</td>
<td>

    <?php
    trait Foo
    {
    }
    
    class Bar
    {
    }

</td>
   </tr>
  </table>

## One Trait Per File

There should only be one trait defined in a file.
  <table>
   <tr>
    <th>Valid: Only one trait in the file.</th>
    <th>Invalid: Multiple traits defined in one file.</th>
   </tr>
   <tr>
<td>

    <?php
    trait Foo
    {
    }

</td>
<td>

    <?php
    trait Foo
    {
    }
    
    trait Bar
    {
    }

</td>
   </tr>
  </table>

## Multiple Statements On a Single Line

Multiple statements are not allowed on a single line.
  <table>
   <tr>
    <th>Valid: Two statements are spread out on two separate lines.</th>
    <th>Invalid: Two statements are combined onto one line.</th>
   </tr>
   <tr>
<td>

    $foo = 1;
    $bar = 2;

</td>
<td>

    $foo = 1; $bar = 2;

</td>
   </tr>
  </table>

## Aligning Blocks of Assignments

There should be one space on either side of an equals sign used to assign a value to a variable. In the case of a block of related assignments, more space may be inserted to promote readability.
  <table>
   <tr>
    <th>Valid: Equals signs aligned.</th>
    <th>Invalid: Not aligned; harder to read.</th>
   </tr>
   <tr>
<td>

    $shortVar        = (1 + 2);
    $veryLongVarName = 'string';
    $var             = foo($bar, $baz);

</td>
<td>

    $shortVar = (1 + 2);
    $veryLongVarName = 'string';
    $var = foo($bar, $baz);

</td>
   </tr>
  </table>
When using plus-equals, minus-equals etc. still ensure the equals signs are aligned to one space after the longest variable name.
  <table>
   <tr>
    <th>Valid: Equals signs aligned; only one space after longest var name.</th>
    <th>Invalid: Two spaces after longest var name.</th>
   </tr>
   <tr>
<td>

    $shortVar       += 1;
    $veryLongVarName = 1;

</td>
<td>

    $shortVar        += 1;
    $veryLongVarName  = 1;

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Equals signs aligned.</th>
    <th>Invalid: Equals signs not aligned.</th>
   </tr>
   <tr>
<td>

    $shortVar         = 1;
    $veryLongVarName -= 1;

</td>
<td>

    $shortVar        = 1;
    $veryLongVarName -= 1;

</td>
   </tr>
  </table>

## No Space After Cast

Spaces are not allowed after casting operators.
  <table>
   <tr>
    <th>Valid: A cast operator is immediately before its value.</th>
    <th>Invalid: A cast operator is followed by whitespace.</th>
   </tr>
   <tr>
<td>

    $foo = (string)1;

</td>
<td>

    $foo = (string) 1;

</td>
   </tr>
  </table>

## Space After Cast

Exactly one space is allowed after a cast.
  <table>
   <tr>
    <th>Valid: A cast operator is followed by one space.</th>
    <th>Invalid: A cast operator is not followed by whitespace.</th>
   </tr>
   <tr>
<td>

    $foo = (string) 1;

</td>
<td>

    $foo = (string)1;

</td>
   </tr>
  </table>

## Space After NOT operator

Exactly one space is allowed after the NOT operator.
  <table>
   <tr>
    <th>Valid: A NOT operator followed by one space.</th>
    <th>Invalid: A NOT operator not followed by whitespace or followed by too much whitespace.</th>
   </tr>
   <tr>
<td>

    if (! $someVar || ! $x instanceOf stdClass) {};

</td>
<td>

    if (!$someVar || !$x instanceOf stdClass) {};
    
    if (!     $someVar || !
        $x instanceOf stdClass) {};

</td>
   </tr>
  </table>

## Space Before Cast

There should be exactly one space before a cast operator.
  <table>
   <tr>
    <th>Valid: Single space before a cast operator.</th>
    <th>Invalid: No space or multiple spaces before a cast operator.</th>
   </tr>
   <tr>
<td>

    $integer = (int) $string;
    $c = $a . (string) $b;

</td>
<td>

    $integer =(int) $string;
    $c = $a .   (string) $b;

</td>
   </tr>
  </table>

## Call-Time Pass-By-Reference

Call-time pass-by-reference is not allowed. It should be declared in the function definition.
  <table>
   <tr>
    <th>Valid: Pass-by-reference is specified in the function definition.</th>
    <th>Invalid: Pass-by-reference is done in the call to a function.</th>
   </tr>
   <tr>
<td>

    function foo(&$bar)
    {
        $bar++;
    }
    
    $baz = 1;
    foo($baz);

</td>
<td>

    function foo($bar)
    {
        $bar++;
    }
    
    $baz = 1;
    foo(&$baz);

</td>
   </tr>
  </table>

## Function Call Argument Spacing

There should be no space before and exactly one space, or a new line, after a comma when passing arguments to a function or method.
  <table>
   <tr>
    <th>Valid: No space before and exactly one space after a comma.</th>
    <th>Invalid: A space before and no space after a comma.</th>
   </tr>
   <tr>
<td>

    foo($bar, $baz);

</td>
<td>

    foo($bar ,$baz);

</td>
   </tr>
  </table>

## Opening Function Brace Bsd Allman

Function declarations must follow the &quot;BSD/Allman style&quot;. The opening brace is on the line  
following the function declaration and is indented to the same column as the start of the  
function declaration. The brace must be the last content on the line.
  <table>
   <tr>
    <th>Valid: Opening brace on the next line.</th>
    <th>Invalid: Opening brace on the same line.</th>
   </tr>
   <tr>
<td>

    function fooFunction($arg1, $arg2 = '')
    {
        // Do something
    }

</td>
<td>

    function fooFunction($arg1, $arg2 = '') {
        // Do something
    }

</td>
   </tr>
  </table>

## Opening Function Brace Kerninghan Ritchie

The function opening brace must be on the same line as the end of the function declaration, with  
exactly one space between the end of the declaration and the brace. The brace must be the last  
content on the line.
  <table>
   <tr>
    <th>Valid: Opening brace on the same line.</th>
    <th>Invalid: Opening brace on the next line.</th>
   </tr>
   <tr>
<td>

    function fooFunction($arg1, $arg2 = '') {
        // Do something.
    }

</td>
<td>

    function fooFunction($arg1, $arg2 = '')
    {
        // Do something.
    }

</td>
   </tr>
  </table>

## Cyclomatic Complexity

Functions should not have a cyclomatic complexity greater than 20, and should try to stay below a complexity of 10.

## Nesting Level

Functions should not have a nesting level greater than 10, and should try to stay below 5.

## Abstract class name

Abstract class names must be prefixed with &quot;Abstract&quot;, e.g. AbstractBar.
  <table>
   <tr>
    <th>Valid: Class name starts with 'Abstract'.</th>
    <th>Invalid: Class name does not start with 'Abstract'.</th>
   </tr>
   <tr>
<td>

    abstract class AbstractBar
    {
    }

</td>
<td>

    abstract class Bar
    {
    }

</td>
   </tr>
  </table>

## camelCaps Function Names

Functions should use camelCaps format for their names. Only PHP&#039;s magic methods should use a double underscore prefix.
  <table>
   <tr>
    <th>Valid: A function in camelCaps format.</th>
    <th>Invalid: A function in snake_case format.</th>
   </tr>
   <tr>
<td>

    function doSomething()
    {
    }

</td>
<td>

    function do_something()
    {
    }

</td>
   </tr>
  </table>

## Constructor name

Constructors should be named __construct, not after the class.
  <table>
   <tr>
    <th>Valid: The constructor is named __construct.</th>
    <th>Invalid: The old style class name constructor is used.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        function __construct()
        {
        }
    }

</td>
<td>

    class Foo
    {
        function Foo()
        {
        }
    }

</td>
   </tr>
  </table>

## Interface name suffix

Interface names must be suffixed with &quot;Interface&quot;, e.g. BarInterface.
  <table>
   <tr>
    <th>Valid: Interface name ends on 'Interface'.</th>
    <th>Invalid: Interface name does not end on 'Interface'.</th>
   </tr>
   <tr>
<td>

    interface BarInterface
    {
    }

</td>
<td>

    interface Bar
    {
    }

</td>
   </tr>
  </table>

## Trait name suffix

Trait names must be suffixed with &quot;Trait&quot;, e.g. BarTrait.
  <table>
   <tr>
    <th>Valid: Trait name ends on 'Trait'.</th>
    <th>Invalid: Trait name does not end on 'Trait'.</th>
   </tr>
   <tr>
<td>

    trait BarTrait
    {
    }

</td>
<td>

    trait Bar
    {
    }

</td>
   </tr>
  </table>

## Constant Names

Constants should always be all-uppercase, with underscores to separate words.
  <table>
   <tr>
    <th>Valid: All uppercase constant name.</th>
    <th>Invalid: Mixed case or lowercase constant name.</th>
   </tr>
   <tr>
<td>

    define('FOO_CONSTANT', 'foo');
    
    class FooClass
    {
        const FOO_CONSTANT = 'foo';
    }

</td>
<td>

    define('Foo_Constant', 'foo');
    
    class FooClass
    {
        const foo_constant = 'foo';
    }

</td>
   </tr>
  </table>

## Backtick Operator

Disallow the use of the backtick operator for execution of shell commands.

## Opening Tag at Start of File

The opening PHP tag should be the first item in the file.
  <table>
   <tr>
    <th>Valid: A file starting with an opening PHP tag.</th>
    <th>Invalid: A file with content before the opening PHP tag.</th>
   </tr>
   <tr>
<td>

    <?php
    echo 'Foo';

</td>
<td>

    Beginning content
    <?php
    echo 'Foo';

</td>
   </tr>
  </table>

## Closing PHP Tags

All opening PHP tags should have a corresponding closing tag.
  <table>
   <tr>
    <th>Valid: A closing tag paired with its opening tag.</th>
    <th>Invalid: No closing tag paired with the opening tag.</th>
   </tr>
   <tr>
<td>

    <?php
    echo 'Foo';
    ?>

</td>
<td>

    <?php
    echo 'Foo';

</td>
   </tr>
  </table>

## Deprecated Functions

Deprecated functions should not be used.
  <table>
   <tr>
    <th>Valid: A non-deprecated function is used.</th>
    <th>Invalid: A deprecated function is used.</th>
   </tr>
   <tr>
<td>

    $foo = explode('a', $bar);

</td>
<td>

    $foo = split('a', $bar);

</td>
   </tr>
  </table>

## Alternative PHP Code Tags

Always use &lt;?php ?&gt; to delimit PHP code, do not use the ASP &lt;% %&gt; style tags nor the &lt;script language=&quot;php&quot;&gt;&lt;/script&gt; tags. This is the most portable way to include PHP code on differing operating systems and setups.

## $_REQUEST Superglobal

$_REQUEST should never be used due to the ambiguity created as to where the data is coming from. Use $_POST, $_GET, or $_COOKIE instead.

## PHP Code Tags

Always use &lt;?php ?&gt; to delimit PHP code, not the &lt;? ?&gt; shorthand. This is the most portable way to include PHP code on differing operating systems and setups.

## Goto

Discourage the use of the PHP `goto` language construct.

## Forbidden Functions

The forbidden functions sizeof() and delete() should not be used.
  <table>
   <tr>
    <th>Valid: count() is used in place of sizeof().</th>
    <th>Invalid: sizeof() is used.</th>
   </tr>
   <tr>
<td>

    $foo = count($bar);

</td>
<td>

    $foo = sizeof($bar);

</td>
   </tr>
  </table>

## Lowercase PHP Constants

The *true*, *false* and *null* constants must always be lowercase.
  <table>
   <tr>
    <th>Valid: Lowercase constants.</th>
    <th>Invalid: Uppercase constants.</th>
   </tr>
   <tr>
<td>

    if ($var === false || $var === null) {
        $var = true;
    }

</td>
<td>

    if ($var === FALSE || $var === NULL) {
        $var = TRUE;
    }

</td>
   </tr>
  </table>

## Lowercase Keywords

All PHP keywords should be lowercase.
  <table>
   <tr>
    <th>Valid: Lowercase array keyword used.</th>
    <th>Invalid: Non-lowercase array keyword used.</th>
   </tr>
   <tr>
<td>

    $foo = array();

</td>
<td>

    $foo = Array();

</td>
   </tr>
  </table>

## Lowercase PHP Types

All PHP types used for parameter type and return type declarations should be lowercase.
  <table>
   <tr>
    <th>Valid: Lowercase type declarations used.</th>
    <th>Invalid: Non-lowercase type declarations used.</th>
   </tr>
   <tr>
<td>

    function myFunction(int $foo) : string {
    }

</td>
<td>

    function myFunction(Int $foo) : STRING {
    }

</td>
   </tr>
  </table>
All PHP types used for type casting should be lowercase.
  <table>
   <tr>
    <th>Valid: Lowercase type used.</th>
    <th>Invalid: Non-lowercase type used.</th>
   </tr>
   <tr>
<td>

    $foo = (bool) $isValid;

</td>
<td>

    $foo = (BOOL) $isValid;

</td>
   </tr>
  </table>

## Silenced Errors

Suppressing Errors is not allowed.
  <table>
   <tr>
    <th>Valid: isset() is used to verify that a variable exists before trying to use it.</th>
    <th>Invalid: Errors are suppressed.</th>
   </tr>
   <tr>
<td>

    if (isset($foo) && $foo) {
        echo "Hello\n";
    }

</td>
<td>

    if (@$foo) {
        echo "Hello\n";
    }

</td>
   </tr>
  </table>

## Require Strict Types

The strict_types declaration must be present.
  <table>
   <tr>
    <th>Valid: `strict_types` declaration is present.</th>
    <th>Invalid: Missing `strict_types` declaration.</th>
   </tr>
   <tr>
<td>

    declare(strict_types=1);
    
    declare(encoding='UTF-8', strict_types=0);

</td>
<td>

    declare(encoding='ISO-8859-1');

</td>
   </tr>
  </table>
The strict_types declaration must be enabled.
  <table>
   <tr>
    <th>Valid: `strict_types` declaration is enabled.</th>
    <th>Invalid: `strict_types` declaration is disabled.</th>
   </tr>
   <tr>
<td>

    declare(strict_types=1);

</td>
<td>

    declare(strict_types=0);

</td>
   </tr>
  </table>

## SAPI Usage

The PHP_SAPI constant should be used instead of php_sapi_name().
  <table>
   <tr>
    <th>Valid: PHP_SAPI is used.</th>
    <th>Invalid: Function call to php_sapi_name() is used.</th>
   </tr>
   <tr>
<td>

    if (PHP_SAPI === 'cli') {
        echo "Hello, CLI user.";
    }

</td>
<td>

    if (php_sapi_name() === 'cli') {
        echo "Hello, CLI user.";
    }

</td>
   </tr>
  </table>

## PHP Syntax

The code should use valid PHP syntax.
  <table>
   <tr>
    <th>Valid: No PHP syntax errors.</th>
    <th>Invalid: Code contains PHP syntax errors.</th>
   </tr>
   <tr>
<td>

    echo "Hello!";
    $array = [1, 2, 3];

</td>
<td>

    echo "Hello!" // Missing semicolon.
    $array = [1, 2, 3; // Missing closing bracket.

</td>
   </tr>
  </table>

## Uppercase PHP Constants

The *true*, *false* and *null* constants must always be uppercase.
  <table>
   <tr>
    <th>Valid: Uppercase constants.</th>
    <th>Invalid: Lowercase constants.</th>
   </tr>
   <tr>
<td>

    if ($var === FALSE || $var === NULL) {
        $var = TRUE;
    }

</td>
<td>

    if ($var === false || $var === null) {
        $var = true;
    }

</td>
   </tr>
  </table>

## Unnecessary Heredoc

If no interpolation or expressions are used in the body of a heredoc, nowdoc syntax should be used instead.
  <table>
   <tr>
    <th>Valid: Using nowdoc syntax for a text string without any interpolation or expressions.</th>
    <th>Invalid: Using heredoc syntax for a text string without any interpolation or expressions.</th>
   </tr>
   <tr>
<td>

    $nowdoc = <<<'EOD'
    some text
    EOD;

</td>
<td>

    $heredoc = <<<EOD
    some text
    EOD;

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Using heredoc syntax for a text string containing interpolation or expressions.</th>
    <th>Invalid: Using heredoc syntax for a text string without any interpolation or expressions.</th>
   </tr>
   <tr>
<td>

    $heredoc = <<<"EOD"
    some $text
    EOD;

</td>
<td>

    $heredoc = <<<"EOD"
    some text
    EOD;

</td>
   </tr>
  </table>

## Unnecessary String Concatenation

Strings should not be concatenated together.
  <table>
   <tr>
    <th>Valid: A string can be concatenated with an expression.</th>
    <th>Invalid: Strings should not be concatenated together.</th>
   </tr>
   <tr>
<td>

    echo '5 + 2 = ' . (5 + 2);

</td>
<td>

    echo 'Hello' . ' ' . 'World';

</td>
   </tr>
  </table>

## Subversion Properties

All PHP files in a subversion repository should have the svn:keywords property set to &#039;Author Id Revision&#039; and the svn:eol-style property set to &#039;native&#039;.

## Arbitrary Parentheses Spacing

Arbitrary sets of parentheses should have no spaces inside.
  <table>
   <tr>
    <th>Valid: No spaces on the inside of a set of arbitrary parentheses.</th>
    <th>Invalid: Spaces or new lines on the inside of a set of arbitrary parentheses.</th>
   </tr>
   <tr>
<td>

    $a = (null !== $extra);

</td>
<td>

    $a = ( null !== $extra );
    
    $a = (
        null !== $extra
    );

</td>
   </tr>
  </table>

## No Space Indentation

Tabs should be used for indentation instead of spaces.

## No Tab Indentation

Spaces should be used for indentation instead of tabs.

## Heredoc Nowdoc Identifier Spacing

There should be no space between the &lt;&lt;&lt; and the heredoc/nowdoc identifier string.
  <table>
   <tr>
    <th>Valid: No space between the <<< and the identifier string.</th>
    <th>Invalid: Whitespace between the <<< and the identifier string.</th>
   </tr>
   <tr>
<td>

    $heredoc = <<<EOD
    some text
    EOD;

</td>
<td>

    $heredoc = <<<   END
    some text
    END;

</td>
   </tr>
  </table>

## Increment Decrement Spacing

There should be no whitespace between variables and increment/decrement operators.
  <table>
   <tr>
    <th>Valid: No whitespace between variables and increment/decrement operators.</th>
    <th>Invalid: Whitespace between variables and increment/decrement operators.</th>
   </tr>
   <tr>
<td>

    ++$i;
    --$i['key']['id'];
    ClassName::$prop++;
    $obj->prop--;

</td>
<td>

    ++ $i;
    --   $i['key']['id'];
    ClassName::$prop    ++;
    $obj->prop
    --;

</td>
   </tr>
  </table>

## Language Construct Spacing

Language constructs that can be used without parentheses, must have a single space between the language construct keyword and its content.
  <table>
   <tr>
    <th>Valid: Single space after language construct.</th>
    <th>Invalid: No space, more than one space or newline after language construct.</th>
   </tr>
   <tr>
<td>

    echo 'Hello, World!';
    throw new Exception();
    return $newLine;

</td>
<td>

    echo'Hello, World!';
    throw   new   Exception();
    return
    $newLine;

</td>
   </tr>
  </table>
A single space must be used between the &quot;yield&quot; and &quot;from&quot; keywords for a &quot;yield from&quot; expression.
  <table>
   <tr>
    <th>Valid: Single space between yield and from.</th>
    <th>Invalid: More than one space or newline between yield and from.</th>
   </tr>
   <tr>
<td>

    function myGenerator() {
        yield from [1, 2, 3];
    }

</td>
<td>

    function myGenerator() {
        yield  from [1, 2, 3];
        yield
        from [1, 2, 3];
    }

</td>
   </tr>
  </table>

## Scope Indentation

Indentation for control structures, classes, and functions should be 4 spaces per level.
  <table>
   <tr>
    <th>Valid: 4 spaces are used to indent a control structure.</th>
    <th>Invalid: 8 spaces are used to indent a control structure.</th>
   </tr>
   <tr>
<td>

    if ($test) {
        $var = 1;
    }

</td>
<td>

    if ($test) {
            $var = 1;
    }

</td>
   </tr>
  </table>

## Spacing After Spread Operator

There should be no space between the spread operator and the variable/function call it applies to.
  <table>
   <tr>
    <th>Valid: No space between the spread operator and the variable/function call it applies to.</th>
    <th>Invalid: Space found between the spread operator and the variable/function call it applies to.</th>
   </tr>
   <tr>
<td>

    function foo(&...$spread) {
        bar(...$spread);
    
        bar(
            [...$foo],
            ...array_values($keyedArray)
        );
    }

</td>
<td>

    function bar(... $spread) {
        bar(...
            $spread
        );
    
        bar(
            [... $foo ],.../*@*/array_values($keyed)
        );
    }

</td>
   </tr>
  </table>

## Default Values in Function Declarations

Arguments with default values go at the end of the argument list.
  <table>
   <tr>
    <th>Valid: Argument with default value at end of declaration.</th>
    <th>Invalid: Argument with default value at start of declaration.</th>
   </tr>
   <tr>
<td>

    function connect($dsn, $persistent = false)
    {
        ...
    }

</td>
<td>

    function connect($persistent = false, $dsn)
    {
        ...
    }

</td>
   </tr>
  </table>

## Class Declaration

Each class must be in a file by itself and must be under a namespace (a top-level vendor name).
  <table>
   <tr>
    <th>Valid: One class in a file.</th>
    <th>Invalid: Multiple classes in a single file.</th>
   </tr>
   <tr>
<td>

    <?php
    namespace Foo;
    
    class Bar {
    }

</td>
<td>

    <?php
    namespace Foo;
    
    class Bar {
    }
    
    class Baz {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: A vendor-level namespace is used.</th>
    <th>Invalid: No namespace used in file.</th>
   </tr>
   <tr>
<td>

    <?php
    namespace Foo;
    
    class Bar {
    }

</td>
<td>

    <?php
    class Bar {
    }

</td>
   </tr>
  </table>

## Side Effects

A PHP file should either contain declarations with no side effects, or should just have logic (including side effects) with no declarations.
  <table>
   <tr>
    <th>Valid: A class defined in a file by itself.</th>
    <th>Invalid: A class defined in a file with other code.</th>
   </tr>
   <tr>
<td>

    <?php
    class Foo
    {
    }

</td>
<td>

    <?php
    class Foo
    {
    }
    
    echo "Class Foo loaded.";

</td>
   </tr>
  </table>

## Method Name

Method names MUST be declared in camelCase.
  <table>
   <tr>
    <th>Valid: Method name in camelCase.</th>
    <th>Invalid: Method name not in camelCase.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        private function doBar()
        {
        }
    }

</td>
<td>

    class Foo
    {
        private function do_bar()
        {
        }
    }

</td>
   </tr>
  </table>

## Class Declarations

There should be exactly 1 space between the abstract or final keyword and the class keyword and between the class keyword and the class name.  The extends and implements keywords, if present, must be on the same line as the class name.  When interfaces implemented are spread over multiple lines, there should be exactly 1 interface mentioned per line indented by 1 level.  The closing brace of the class must go on the first line after the body of the class and must be on a line by itself.
  <table>
   <tr>
    <th>Valid: Correct spacing around class keyword.</th>
    <th>Invalid: 2 spaces used around class keyword.</th>
   </tr>
   <tr>
<td>

    abstract class Foo
    {
    }

</td>
<td>

    abstract  class  Foo
    {
    }

</td>
   </tr>
  </table>

## Property Declarations

Property names should not be prefixed with an underscore to indicate visibility.  Visibility should be used to declare properties rather than the var keyword.  Only one property should be declared within a statement.  The static declaration must come after the visibility declaration.
  <table>
   <tr>
    <th>Valid: Correct property naming.</th>
    <th>Invalid: An underscore prefix used to indicate visibility.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        private $bar;
    }

</td>
<td>

    class Foo
    {
        private $_bar;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Visibility of property declared.</th>
    <th>Invalid: Var keyword used to declare property.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        private $bar;
    }

</td>
<td>

    class Foo
    {
        var $bar;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: One property declared per statement.</th>
    <th>Invalid: Multiple properties declared in one statement.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        private $bar;
        private $baz;
    }

</td>
<td>

    class Foo
    {
        private $bar, $baz;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: If declared as static, the static declaration must come after the visibility declaration.</th>
    <th>Invalid: Static declaration before the visibility declaration.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        public static $bar;
        private $baz;
    }

</td>
<td>

    class Foo
    {
        static protected $bar;
    }

</td>
   </tr>
  </table>

## Control Structure Spacing

Control Structures should have 0 spaces after opening parentheses and 0 spaces before closing parentheses.
  <table>
   <tr>
    <th>Valid: Correct spacing.</th>
    <th>Invalid: Whitespace used inside the parentheses.</th>
   </tr>
   <tr>
<td>

    if ($foo) {
        $var = 1;
    }

</td>
<td>

    if ( $foo ) {
        $var = 1;
    }

</td>
   </tr>
  </table>

## Elseif Declarations

PHP&#039;s elseif keyword should be used instead of else if.
  <table>
   <tr>
    <th>Valid: Single word elseif keyword used.</th>
    <th>Invalid: Separate else and if keywords used.</th>
   </tr>
   <tr>
<td>

    if ($foo) {
        $var = 1;
    } elseif ($bar) {
        $var = 2;
    }

</td>
<td>

    if ($foo) {
        $var = 1;
    } else if ($bar) {
        $var = 2;
    }

</td>
   </tr>
  </table>

## Switch Declaration

Case and default keywords must be lowercase.
  <table>
   <tr>
    <th>Valid: Keywords in lowercase.</th>
    <th>Invalid: Keywords not in lowercase.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            break;
        default:
            break;
    }

</td>
<td>

    switch ($foo) {
        CASE 'bar':
            break;
        Default:
            break;
    }

</td>
   </tr>
  </table>
Case statements must be followed by exactly one space.
  <table>
   <tr>
    <th>Valid: Case statement followed by one space.</th>
    <th>Invalid: Case statement not followed by one space.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            break;
    }

</td>
<td>

    switch ($foo) {
        case'bar':
            break;
    }

</td>
   </tr>
  </table>
There must be no whitespace between the case value or default keyword and the colon.
  <table>
   <tr>
    <th>Valid: Colons not preceded by whitespace.</th>
    <th>Invalid: Colons preceded by whitespace.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            break;
        default:
            break;
    }

</td>
<td>

    switch ($foo) {
        case 'bar' :
            break;
        default :
            break;
    }

</td>
   </tr>
  </table>
The case or default body must start on the line following the statement.
  <table>
   <tr>
    <th>Valid: Body starts on the next line.</th>
    <th>Invalid: Body on the same line as the case statement.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            break;
    }

</td>
<td>

    switch ($foo) {
        case 'bar': break;
    }

</td>
   </tr>
  </table>
Terminating statements must be on a line by themselves.
  <table>
   <tr>
    <th>Valid: Terminating statement on its own line.</th>
    <th>Invalid: Terminating statement not on its own line.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            echo $foo;
            return;
    }

</td>
<td>

    switch ($foo) {
        case 'bar':
            echo $foo; return;
    }

</td>
   </tr>
  </table>
Terminating statements must be indented four more spaces from the case statement.
  <table>
   <tr>
    <th>Valid: Break statement indented correctly.</th>
    <th>Invalid: Break statement not indented four spaces.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            break;
    }

</td>
<td>

    switch ($foo) {
        case 'bar':
        break;
    }

</td>
   </tr>
  </table>
Case and default statements must be defined using a colon.
  <table>
   <tr>
    <th>Valid: Using a colon for case and default statements.</th>
    <th>Invalid: Using a semi-colon or colon followed by braces.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            break;
        default:
            break;
    }

</td>
<td>

    switch ($foo) {
        case 'bar';
            break;
        default: {
            break;
        }
    }

</td>
   </tr>
  </table>
There must be a comment when fall-through is intentional in a non-empty case body.
  <table>
   <tr>
    <th>Valid: Comment marking intentional fall-through in a non-empty case body.</th>
    <th>Invalid: No comment marking intentional fall-through in a non-empty case body.</th>
   </tr>
   <tr>
<td>

    switch ($foo) {
        case 'bar':
            echo $foo;
            // no break
        default:
            break;
    }

</td>
<td>

    switch ($foo) {
        case 'bar':
            echo $foo;
        default:
            break;
    }

</td>
   </tr>
  </table>

## Closing Tag

Checks that the file does not end with a closing tag.
  <table>
   <tr>
    <th>Valid: Closing tag not used.</th>
    <th>Invalid: Closing tag used.</th>
   </tr>
   <tr>
<td>

    <?php
    echo 'Foo';
    

</td>
<td>

    <?php
    echo 'Foo';
    ?>

</td>
   </tr>
  </table>

## End File Newline

PHP Files should end with exactly one newline.

## Function Call Signature

Checks that the function call format is correct.
  <table>
   <tr>
    <th>Valid: Correct spacing is used around parentheses.</th>
    <th>Invalid: Incorrect spacing used, too much space around the parentheses.</th>
   </tr>
   <tr>
<td>

    foo($bar, $baz);

</td>
<td>

    foo ( $bar, $baz );

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Correct number of spaces used for indent in a multi-line function call.</th>
    <th>Invalid: Incorrect number of spaces used for indent in a multi-line function call.</th>
   </tr>
   <tr>
<td>

    foo(
        $bar,
        $baz
    );

</td>
<td>

    foo(
      $bar,
          $baz
    );

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Closing parenthesis for a multi-line function call is on a new line after the last parameter.</th>
    <th>Invalid: Closing parenthesis for a multi-line function call is not on a new line after the last parameter.</th>
   </tr>
   <tr>
<td>

    foo(
        $bar,
        $baz
    );

</td>
<td>

    foo(
        $bar,
        $baz);

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: The first argument of a multi-line function call is on a new line.</th>
    <th>Invalid: The first argument of a multi-line function call is not on a new line.</th>
   </tr>
   <tr>
<td>

    foo(
        $bar,
        $baz
    );

</td>
<td>

    foo($bar,
        $baz
    );

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Only one argument per line in a multi-line function call.</th>
    <th>Invalid: Two or more arguments per line in a multi-line function call.</th>
   </tr>
   <tr>
<td>

    foo(
        $bar,
        $baz
    );

</td>
<td>

    foo(
        $bar, $baz
    );

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: No blank lines in a multi-line function call.</th>
    <th>Invalid: Blank line in multi-line function call.</th>
   </tr>
   <tr>
<td>

    foo(
        $bar,
        $baz
    );

</td>
<td>

    foo(
        $bar,
    
        $baz
    );

</td>
   </tr>
  </table>

## Function Closing Brace

Checks that the closing brace of a function goes directly after the body.
  <table>
   <tr>
    <th>Valid: Closing brace directly follows the function body.</th>
    <th>Invalid: Blank line between the function body and the closing brace.</th>
   </tr>
   <tr>
<td>

    function foo()
    {
        echo 'foo';
    }

</td>
<td>

    function foo()
    {
        echo 'foo';
    
    }

</td>
   </tr>
  </table>

## Method Declarations

Method names should not be prefixed with an underscore to indicate visibility.  The static keyword, when present, should come after the visibility declaration, and the final and abstract keywords should come before.
  <table>
   <tr>
    <th>Valid: Correct method naming.</th>
    <th>Invalid: An underscore prefix used to indicate visibility.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        private function bar()
        {
        }
    }

</td>
<td>

    class Foo
    {
        private function _bar()
        {
        }
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Correct ordering of method prefixes.</th>
    <th>Invalid: `static` keyword used before visibility and final used after.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        final public static function bar()
        {
        }
    }

</td>
<td>

    class Foo
    {
        static public final function bar()
        {
        }
    }

</td>
   </tr>
  </table>

## Namespace Declaration

There must be one blank line after the namespace declaration.
  <table>
   <tr>
    <th>Valid: One blank line after the namespace declaration.</th>
    <th>Invalid: No blank line after the namespace declaration.</th>
   </tr>
   <tr>
<td>

    namespace Foo\Bar;
    
    use \Baz;

</td>
<td>

    namespace Foo\Bar;
    use \Baz;

</td>
   </tr>
  </table>

## Namespace Declarations

Each use declaration must contain only one namespace and must come after the first namespace declaration.  There should be one blank line after the final use statement.
  <table>
   <tr>
    <th>Valid: One use declaration per namespace.</th>
    <th>Invalid: Multiple namespaces in a use declaration.</th>
   </tr>
   <tr>
<td>

    use \Foo;
    use \Bar;

</td>
<td>

    use \Foo, \Bar;

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Use statements come after first namespace.</th>
    <th>Invalid: Namespace declared after use.</th>
   </tr>
   <tr>
<td>

    namespace \Foo;
    
    use \Bar;

</td>
<td>

    use \Bar;
    
    namespace \Foo;

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: A single blank line after the final use statement.</th>
    <th>Invalid: No blank line after the final use statement.</th>
   </tr>
   <tr>
<td>

    use \Foo;
    use \Bar;
    
    class Baz
    {
    }

</td>
<td>

    use \Foo;
    use \Bar;
    class Baz
    {
    }

</td>
   </tr>
  </table>

## Class Instantiation

When instantiating a new class, parenthesis MUST always be present even when there are no arguments passed to the constructor.
  <table>
   <tr>
    <th>Valid: Parenthesis used.</th>
    <th>Invalid: Parenthesis not used.</th>
   </tr>
   <tr>
<td>

    new Foo();

</td>
<td>

    new Foo;

</td>
   </tr>
  </table>

## Closing Brace

The closing brace of object-oriented constructs and functions must not be followed by any comment or statement on the same line.
  <table>
   <tr>
    <th>Valid: Closing brace is the last content on the line.</th>
    <th>Invalid: Comment or statement following the closing brace on the same line.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        // Class content.
    }
    
    function bar()
    {
        // Function content.
    }

</td>
<td>

    interface Foo2
    {
        // Interface content.
    } echo 'Hello!';
    
    function bar()
    {
        // Function content.
    } //end bar()

</td>
   </tr>
  </table>

## Opening Brace Space

The opening brace of an object-oriented construct must not be followed by a blank line.
  <table>
   <tr>
    <th>Valid: No blank lines after opening brace.</th>
    <th>Invalid: Blank line after opening brace.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        public function bar()
        {
            // Method content.
        }
    }

</td>
<td>

    class Foo
    {
    
        public function bar()
        {
            // Method content.
        }
    }

</td>
   </tr>
  </table>

## Boolean Operator Placement

Boolean operators between conditions in control structures must always be at the beginning or at the end of the line, not a mix of both.

This rule applies to if/else conditions, while loops and switch/match statements.
  <table>
   <tr>
    <th>Valid: Boolean operator between conditions consistently at the beginning of the line.</th>
    <th>Invalid: Mix of boolean operators at the beginning and the end of the line.</th>
   </tr>
   <tr>
<td>

    if (
        $expr1
        && $expr2
        && ($expr3
        || $expr4)
        && $expr5
    ) {
        // if body.
    }

</td>
<td>

    if (
        $expr1 &&
        ($expr2 || $expr3)
        && $expr4
    ) {
        // if body.
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Boolean operator between conditions consistently at the end of the line.</th>
    <th>Invalid: Mix of boolean operators at the beginning and the end of the line.</th>
   </tr>
   <tr>
<td>

    if (
        $expr1 ||
        ($expr2 || $expr3) &&
        $expr4
    ) {
        // if body.
    }

</td>
<td>

    match (
        $expr1
        && $expr2 ||
        $expr3
    ) {
        // structure body.
    };

</td>
   </tr>
  </table>

## Control Structure Spacing

Single line control structures must have no spaces after the condition opening parenthesis and before the condition closing parenthesis.
  <table>
   <tr>
    <th>Valid: No space after the opening parenthesis in a single-line condition.</th>
    <th>Invalid: Space after the opening parenthesis in a single-line condition.</th>
   </tr>
   <tr>
<td>

    if ($expr) {
    }

</td>
<td>

    if ( $expr) {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: No space before the closing parenthesis in a single-line condition.</th>
    <th>Invalid: Space before the closing parenthesis in a single-line condition.</th>
   </tr>
   <tr>
<td>

    if ($expr) {
    }

</td>
<td>

    if ($expr ) {
    }

</td>
   </tr>
  </table>
The condition of the multi-line control structure must be indented once, placing the first expression on the next line after the opening parenthesis.
  <table>
   <tr>
    <th>Valid: First expression of a multi-line control structure condition block is on the line after the opening parenthesis.</th>
    <th>Invalid: First expression of a multi-line control structure condition block is on the same line as the opening parenthesis.</th>
   </tr>
   <tr>
<td>

    while (
        $expr1
        && $expr2
    ) {
    }

</td>
<td>

    while ($expr1
        && $expr2
    ) {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Each line in a multi-line control structure condition block indented at least once. Default indentation is 4 spaces.</th>
    <th>Invalid: Some lines in a multi-line control structure condition block not indented correctly.</th>
   </tr>
   <tr>
<td>

    while (
        $expr1
        && $expr2
    ) {
    }

</td>
<td>

    while (
    $expr1
        && $expr2
      && $expr3
    ) {
    }

</td>
   </tr>
  </table>
The closing parenthesis of the multi-line control structure must be on the next line after the last condition, indented to the same level as the start of the control structure.
  <table>
   <tr>
    <th>Valid: The closing parenthesis of a multi-line control structure condition block is on the line after the last expression.</th>
    <th>Invalid: The closing parenthesis of a multi-line control structure condition block is on the same line as the last expression.</th>
   </tr>
   <tr>
<td>

    while (
        $expr1
        && $expr2
    ) {
    }

</td>
<td>

    while (
        $expr1
        && $expr2) {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: The closing parenthesis of a multi-line control structure condition block is indented to the same level as start of the control structure.</th>
    <th>Invalid: The closing parenthesis of a multi-line control structure condition block is not indented to the same level as start of the control structure.</th>
   </tr>
   <tr>
<td>

    while (
        $expr1
        && $expr2
    ) {
    }

</td>
<td>

    while (
        $expr1
        && $expr2
      ) {
    }

</td>
   </tr>
  </table>

## Import Statement

Import use statements must not begin with a leading backslash.
  <table>
   <tr>
    <th>Valid: Import statement doesn't begin with a leading backslash.</th>
    <th>Invalid: Import statement begins with a leading backslash.</th>
   </tr>
   <tr>
<td>

    <?php
    
    use Vendor\Package\ClassA as A;
    
    class FooBar extends A
    {
        // Class content.
    }

</td>
<td>

    <?php
    
    use \Vendor\Package\ClassA as A;
    
    class FooBar extends A
    {
        // Class content.
    }

</td>
   </tr>
  </table>

## Open PHP Tag

When the opening &lt;?php tag is on the first line of the file, it must be on its own line with no other statements unless it is a file containing markup outside of PHP opening and closing tags.
  <table>
   <tr>
    <th>Valid: Opening PHP tag on a line by itself.</th>
    <th>Invalid: Opening PHP tag not on a line by itself.</th>
   </tr>
   <tr>
<td>

    <?php
    
    echo 'hi';

</td>
<td>

    <?php echo 'hi';

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Opening PHP tag not on a line by itself, but has markup outside the closing PHP tag.</th>
    <th>Invalid: Opening PHP tag not on a line by itself without any markup in the file.</th>
   </tr>
   <tr>
<td>

    <?php declare(strict_types=1); ?>
    <html>
    <body>
        <?php
            // ... additional PHP code ...
        ?>
    </body>
    </html>

</td>
<td>

    <?php declare(strict_types=1); ?>

</td>
   </tr>
  </table>

## Nullable Type Declarations Functions

In nullable type declarations there MUST NOT be a space between the question mark and the type.
  <table>
   <tr>
    <th>Valid: No whitespace used.</th>
    <th>Invalid: Superfluous whitespace used.</th>
   </tr>
   <tr>
<td>

    public function functionName(
        ?string $arg1,
        ?int $arg2
    ): ?string {
    }

</td>
<td>

    public function functionName(
        ? string $arg1,
        ? int $arg2
    ): ? string {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: No unexpected characters.</th>
    <th>Invalid: Unexpected characters used.</th>
   </tr>
   <tr>
<td>

    public function foo(?int $arg): ?string
    {
    }

</td>
<td>

    public function bar(? /* comment */ int $arg): ?
        // nullable for a reason
        string
    {
    }

</td>
   </tr>
  </table>

## Return Type Declaration

For function and closure return type declarations, there must be one space after the colon followed by the type declaration, and no space before the colon.

The colon and the return type declaration have to be on the same line as the argument list closing parenthesis.
  <table>
   <tr>
    <th>Valid: A single space between the colon and type in a return type declaration.</th>
    <th>Invalid: No space between the colon and the type in a return type declaration.</th>
   </tr>
   <tr>
<td>

    $closure = function ( $arg ): string {
       // Closure body.
    };

</td>
<td>

    $closure = function ( $arg ):string {
       // Closure body.
    };

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: No space before the colon in a return type declaration.</th>
    <th>Invalid: One or more spaces before the colon in a return type declaration.</th>
   </tr>
   <tr>
<td>

    function someFunction( $arg ): string {
       // Function body.
    };

</td>
<td>

    function someFunction( $arg )   : string {
       // Function body.
    };

</td>
   </tr>
  </table>

## Short Form Type Keywords

Short form of type keywords MUST be used i.e. bool instead of boolean, int instead of integer etc.
  <table>
   <tr>
    <th>Valid: Short form type used.</th>
    <th>Invalid: Long form type type used.</th>
   </tr>
   <tr>
<td>

    $foo = (bool) $isValid;

</td>
<td>

    $foo = (boolean) $isValid;

</td>
   </tr>
  </table>

## Compound Namespace Depth

Compound namespaces with a depth of more than two MUST NOT be used.
  <table>
   <tr>
    <th>Valid: Max depth of 2.</th>
    <th>Invalid: Max depth of 3.</th>
   </tr>
   <tr>
<td>

    use Vendor\Package\SomeNamespace\{
        SubnamespaceOne\ClassA,
        SubnamespaceOne\ClassB,
        SubnamespaceTwo\ClassY,
        ClassZ,
    };

</td>
<td>

    use Vendor\Package\SomeNamespace\{
        SubnamespaceOne\AnotherNamespace\ClassA,
        SubnamespaceOne\ClassB,
        ClassZ,
    };

</td>
   </tr>
  </table>

## Operator Spacing

All binary and ternary (but not unary) operators MUST be preceded and followed by at least one space. This includes all arithmetic, comparison, assignment, bitwise, logical (excluding ! which is unary), string concatenation, type operators, trait operators (insteadof and as), and the single pipe operator (e.g. ExceptionType1 | ExceptionType2 $e).
  <table>
   <tr>
    <th>Valid: At least 1 space used.</th>
    <th>Invalid: No spacing used.</th>
   </tr>
   <tr>
<td>

    if ($a === $b) {
        $foo = $bar ?? $a ?? $b;
    } elseif ($a > $b) {
        $variable = $foo ? 'foo' : 'bar';
    }

</td>
<td>

    if ($a===$b) {
        $foo=$bar??$a??$b;
    } elseif ($a>$b) {
        $variable=$foo?'foo':'bar';
    }

</td>
   </tr>
  </table>

## Constant Visibility

Visibility must be declared on all class constants if your project PHP minimum version supports constant visibilities (PHP 7.1 or later).

The term &quot;class&quot; refers to all classes, interfaces, enums and traits.
  <table>
   <tr>
    <th>Valid: Constant visibility declared.</th>
    <th>Invalid: Constant visibility not declared.</th>
   </tr>
   <tr>
<td>

    class Foo
    {
        private const BAR = 'bar';
    }

</td>
<td>

    class Foo
    {
        const BAR = 'bar';
    }

</td>
   </tr>
  </table>

## Valid Class Name

Class names must be written in Pascal case. This means that it starts with a capital letter, and the first letter of each word in the class name is capitalized. Only letters and numbers are allowed.
  <table>
   <tr>
    <th>Valid: Class name starts with a capital letter.</th>
    <th>Invalid: Class name does not start with a capital letter.</th>
   </tr>
   <tr>
<td>

    class PascalCaseStandard
    {
    }

</td>
<td>

    class notPascalCaseStandard
    {
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Class name contains only letters and numbers.</th>
    <th>Invalid: Class name contains underscores.</th>
   </tr>
   <tr>
<td>

    class PSR7Response
    {
    }

</td>
<td>

    class PSR7_Response
    {
    }

</td>
   </tr>
  </table>

## Foreach Loop Declarations

There should be a space between each element of a foreach loop and the as keyword should be lowercase.
  <table>
   <tr>
    <th>Valid: Correct spacing used.</th>
    <th>Invalid: Invalid spacing used.</th>
   </tr>
   <tr>
<td>

    foreach ($foo as $bar => $baz) {
        echo $baz;
    }

</td>
<td>

    foreach ( $foo  as  $bar=>$baz ) {
        echo $baz;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Lowercase as keyword.</th>
    <th>Invalid: Uppercase as keyword.</th>
   </tr>
   <tr>
<td>

    foreach ($foo as $bar => $baz) {
        echo $baz;
    }

</td>
<td>

    foreach ($foo AS $bar => $baz) {
        echo $baz;
    }

</td>
   </tr>
  </table>

## For Loop Declarations

In a for loop declaration, there should be no space inside the brackets and there should be 0 spaces before and 1 space after semicolons.
  <table>
   <tr>
    <th>Valid: Correct spacing used.</th>
    <th>Invalid: Invalid spacing used inside brackets.</th>
   </tr>
   <tr>
<td>

    for ($i = 0; $i < 10; $i++) {
        echo $i;
    }

</td>
<td>

    for ( $i = 0; $i < 10; $i++ ) {
        echo $i;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Correct spacing used.</th>
    <th>Invalid: Invalid spacing used before semicolons.</th>
   </tr>
   <tr>
<td>

    for ($i = 0; $i < 10; $i++) {
        echo $i;
    }

</td>
<td>

    for ($i = 0 ; $i < 10 ; $i++) {
        echo $i;
    }

</td>
   </tr>
  </table>
  <table>
   <tr>
    <th>Valid: Correct spacing used.</th>
    <th>Invalid: Invalid spacing used after semicolons.</th>
   </tr>
   <tr>
<td>

    for ($i = 0; $i < 10; $i++) {
        echo $i;
    }

</td>
<td>

    for ($i = 0;$i < 10;$i++) {
        echo $i;
    }

</td>
   </tr>
  </table>

## Lowercase Control Structure Keywords

The PHP keywords if, else, elseif, foreach, for, do, switch, while, try, and catch should be lowercase.
  <table>
   <tr>
    <th>Valid: Lowercase if keyword.</th>
    <th>Invalid: Uppercase if keyword.</th>
   </tr>
   <tr>
<td>

    if ($foo) {
        $bar = true;
    }

</td>
<td>

    IF ($foo) {
        $bar = true;
    }

</td>
   </tr>
  </table>

## Lowercase Function Keywords

The PHP keywords function, public, private, protected, and static should be lowercase.
  <table>
   <tr>
    <th>Valid: Lowercase function keyword.</th>
    <th>Invalid: Uppercase function keyword.</th>
   </tr>
   <tr>
<td>

    function foo()
    {
        return true;
    }

</td>
<td>

    FUNCTION foo()
    {
        return true;
    }

</td>
   </tr>
  </table>

## Cast Whitespace

Casts should not have whitespace inside the parentheses.
  <table>
   <tr>
    <th>Valid: No spaces.</th>
    <th>Invalid: Whitespace used inside parentheses.</th>
   </tr>
   <tr>
<td>

    $foo = (int)'42';

</td>
<td>

    $foo = ( int )'42';

</td>
   </tr>
  </table>

## Scope Closing Brace

Indentation of a closing brace must match the indentation of the line containing the opening brace.
  <table>
   <tr>
    <th>Valid: Closing brace aligned with line containing opening brace.</th>
    <th>Invalid: Closing brace misaligned with line containing opening brace.</th>
   </tr>
   <tr>
<td>

    function foo()
    {
    }
    
    if (!class_exists('Foo')) {
        class Foo {
        }
    }
    
    <?php if ($something) { ?>
        <span>some output</span>
    <?php } ?>

</td>
<td>

    function foo()
    {
     }
    
    if (!class_exists('Foo')) {
        class Foo {
    }
        }
    
    <?php if ($something) { ?>
        <span>some output</span>
     <?php } ?>

</td>
   </tr>
  </table>
Closing brace must be on a line by itself.
  <table>
   <tr>
    <th>Valid: Close brace on its own line.</th>
    <th>Invalid: Close brace on a line containing other code.</th>
   </tr>
   <tr>
<td>

    enum Foo {
    }

</td>
<td>

    enum Foo {}

</td>
   </tr>
  </table>

## Scope Keyword Spacing

The PHP keywords static, public, private, and protected should have one space after them.
  <table>
   <tr>
    <th>Valid: A single space following the keywords.</th>
    <th>Invalid: Multiple spaces following the keywords.</th>
   </tr>
   <tr>
<td>

    public static function foo()
    {
    }

</td>
<td>

    public  static  function foo()
    {
    }

</td>
   </tr>
  </table>

## Superfluous Whitespace

There should be no superfluous whitespace at the start of a file.
  <table>
   <tr>
    <th>Valid: No whitespace preceding first content in file.</th>
    <th>Invalid: Whitespace used before content in file.</th>
   </tr>
   <tr>
<td>

    <?php
    echo 'opening PHP tag at start of file';

</td>
<td>

            
    <?php
    echo 'whitespace before opening PHP tag';

</td>
   </tr>
  </table>
There should be no trailing whitespace at the end of lines.
  <table>
   <tr>
    <th>Valid: No whitespace found at end of line.</th>
    <th>Invalid: Whitespace found at end of line.</th>
   </tr>
   <tr>
<td>

    echo 'semicolon followed by new line char';

</td>
<td>

    echo 'trailing spaces after semicolon';   

</td>
   </tr>
  </table>
There should be no consecutive blank lines in functions.
  <table>
   <tr>
    <th>Valid: Functions do not contain multiple empty lines in a row.</th>
    <th>Invalid: Functions contain multiple empty lines in a row.</th>
   </tr>
   <tr>
<td>

    function myFunction()
    {
        echo 'code here';
    
        echo 'code here';
    }

</td>
<td>

    function myFunction()
    {
        echo 'code here';
        
    
        echo 'code here';
    }

</td>
   </tr>
  </table>
There should be no superfluous whitespace after the final closing PHP tag in a file.
  <table>
   <tr>
    <th>Valid: A single new line appears after the last content in the file.</th>
    <th>Invalid: Multiple new lines appear after the last content in the file.</th>
   </tr>
   <tr>
<td>

    function myFunction()
    {
        echo 'Closing PHP tag, then';
        echo 'Single new line char, then EOF';
    }
    
    ?>
    

</td>
<td>

    function myFunction()
    {
        echo 'Closing PHP tag, then';
        echo 'Multiple new line chars, then EOF';
    }
    
    ?>
    
    

</td>
   </tr>
  </table>

Documentation generated on Tue, 13 May 2025 09:18:44 +0000 by [PHP_CodeSniffer 3.13.0](https://github.com/PHPCSStandards/PHP_CodeSniffer)
