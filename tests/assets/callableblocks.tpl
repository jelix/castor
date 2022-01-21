{callableblock 'mytest', $arg1}
    <p>This is my block {$arg1} {$existingVar}</p>
{/callableblock}

{callableblock 'mytest2'}
    <p>This is my block test 2 without parameters, and displaying existingVar={$existingVar} </p>
{/callableblock}

{callableblock 'mytest3', $existingVar}
    <p>This is my block test 3 with a parameter having the same name of a variable.
        arg existingVar={$existingVar} </p>
{/callableblock}

<div class="box">
    {callblock 'mytest', 'toto'}

    {callblock 'mytest2'}

    {callblock 'mytest3', 'tata'}
    Real existingVar={$existingVar}
</div>