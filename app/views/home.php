{--
    @author Alexis Bogado
    @package graphic-framework
--}

@add('base')

@content('contents')
<section class="text-blue">
    <div class="container text-center">
        <h1 class="text-uppercase font-weight-bold">Welcome {{ auth()->user()->username }}!</h1>
        <p>Your ID is <b>{{ auth()->user()->id }}</b></p>
        <p>Your current role is <b>{{ auth()->user()->role()->name }}</b> (ID: {{ auth()->user()->role()->id }})</p>
        <p>Member since <b>{{ date('d-m-Y H:i', strtotime(auth()->user()->created_at)) }}</b></p>
        <hr />
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam quasi ratione culpa! Eveniet blanditiis maiores perferendis nemo exercitationem incidunt aliquid nisi dolorum, magni nesciunt quis sit, quidem laboriosam quia enim.</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque quod accusantium, totam soluta iusto id possimus consequatur aut nihil sit doloremque eaque magnam recusandae numquam est fugiat! Neque, dolorem quia. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Doloribus similique praesentium voluptate necessitatibus saepe quia beatae consequatur aperiam aliquam quibusdam ad eligendi dignissimos tempore rem, sint ex fugit. Quisquam, nulla?</p>
        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. A mollitia quidem dolorem perferendis nihil? Voluptates magnam quos ea expedita debitis odit perferendis molestiae. Beatae saepe, voluptates dolorem velit quos ut. Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta voluptatibus saepe vel omnis quia asperiores laudantium assumenda nisi culpa, natus beatae dolores amet cumque incidunt placeat deleniti aut corrupti cum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta eaque quibusdam fugiat alias distinctio culpa. Temporibus sapiente minus error facilis fugiat. Quam, odit alias iure voluptatum repellat aliquam temporibus laborum?</p>
    </div>
</section>
@endcontent