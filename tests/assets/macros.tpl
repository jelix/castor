{macro 'mytest', $arg1}
    <p>This is my block {$arg1} {$existingVar}</p>
{/macro}

{macro 'mytest2'}
    <p>This is my block test 2 without parameters, and displaying existingVar={$existingVar} </p>
{/macro}

{macro 'mytest3', $existingVar}
    <p>This is my block test 3 with a parameter having the same name of a variable.
        arg existingVar={$existingVar} </p>
{/macro}

<div class="box">
    {usemacro 'mytest', 'toto'}

    {usemacro 'mytest2'}

    {usemacro 'mytest3', 'tata'}
    Real existingVar={$existingVar}
</div>