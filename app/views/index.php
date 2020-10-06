{--
    @author Alexis Bogado
    @package graphic-framework
--}

@add('base')

@content('contents')
<section id="main" class="bg-blue text-white text-center">
    <div>
        <h1 class="text-uppercase">{{ config('app.name') }}</h1>
        <p>A light-weight PHP framework</p>

        <?php if ($showGithubButton): ?>
            <a href="https://www.github.com/alexisbogado" target="_blank" title="GitHub profile" class="btn btn-light text-blue mt-5">
                <i class="fab fa-github mr-2"></i> Visit GitHub profile
            </a>
        <?php endif; ?>
    </div>
</section>

<section id="technologies" class="text-blue">
    <div class="container">
            <h1>Technologies</h1>
    
            <p>Graphic Framework has been developed with <b>PHP</b>, it uses the <b>MVC architecture</b> as well as PDO to manage the MySQL connections.</p>
            
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A ipsa fugiat, error voluptas maiores officia totam excepturi dolorum, itaque, velit nobis reiciendis vitae quaerat. Corporis dicta expedita quibusdam id? Inventore. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatum nulla repudiandae culpa iste similique sint facilis, laudantium corrupti eum, sed ea suscipit animi quis quos dolore in, fugiat tenetur tempore?</p>

            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsa totam est, quisquam modi, et magni quas, ut tempore ducimus accusantium eveniet ratione autem excepturi voluptatibus similique? Illum perspiciatis hic laudantium! Lorem ipsum dolor sit amet consectetur, adipisicing elit. Debitis ex quasi enim autem, quos tempore error ratione adipisci sit inventore explicabo, deleniti alias harum molestiae molestias accusantium ea fugit minima. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Fugiat asperiores laborum nemo distinctio deleniti omnis, officia velit temporibus nobis aliquam, ut, amet sint maxime quidem quisquam eos quis nihil quasi.</p>
        </div>
    </div>
</section>
@endcontent