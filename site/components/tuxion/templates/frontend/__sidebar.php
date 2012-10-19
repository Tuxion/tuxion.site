<?php namespace components\tuxion; if(!defined('TX')) die('No direct access.'); ?>

<div class="col small">

  <img id="logo" src="<?php echo URL_THEMES; ?>custom/tuxion/img/logo-<?php echo ($data->view == 'light' ? 'black-blue' : 'white'); ?>.png" alt="Tuxion logo" />

  <section id="tuxion" class="item blue solid">
    <header><h1>Tuxion, aangenaam</h1></header>
    <p>
      Wij zijn een jong, creatief team gespecialiseerd in het ontwikkelen van <b>websites</b> en <b>webapplicaties</b>. Wij zijn altijd op zoek naar uitdagende projecten, zowel op technisch vlak als in design.
    </p>
    <p>
      Wij zijn benieuwd naar uw idee&euml;n. <a class="menu-item-link" href="#contact" title="Contact">Vertel ons iets over uw project!</a>
    </p>
  </section>

  <section id="team" class="item blue solid">
    <header><h1>Het team</h1></header>

    <ul id="team-members">
      <li class="robin" itemscope itemtype="http://schema.org/Person">
        <span class="name" itemprop="name">Robin van Boven</span>
      </li>
      <li class="jeroen" itemscope itemtype="http://schema.org/Person">
        <span class="name" itemprop="name">Jeroen Hofman</span>
      </li>
      <li class="adrian" itemscope itemtype="http://schema.org/Person">
        <span class="name" itemprop="name">Adrian Klingen</span>
      </li>
      <li class="bart" itemscope itemtype="http://schema.org/Person">
        <span class="name" itemprop="name">Bart Roorda</span>
      </li>
      <li class="janine" itemscope itemtype="http://schema.org/Person">
        <span class="name" itemprop="name">Janine Terlouw</span>
      </li>
      <li class="aldwin" itemscope itemtype="http://schema.org/Person">
        <span class="name" itemprop="name">Aldwin Vlasblom</span>
      </li>
    </ul>
    <!-- Mijn favoriete websites -->
    <!-- Mijn code-to-be-proud-of -->
    <!-- Mijn laatste posts -->
    <!-- Mijn favoriete dingen, "Loves: []" -->
    <!-- Ik online, wellicht in kleine iconen a la http://www.peppered.nl/contact/ (sidebar: Medewerkers)  of http://supersteil.com/about#!team-->
    <!-- Laatste tweet(?) -->
    <!-- Random stukje inspirationele tekst, wissellijst -->
  </section>

  <section id="about" class="item blue solid">
    <header><h1>Over ons</h1></header>
    <p>
      Tuxion is een uniek, innovatief webbureau gevestigd in Rotterdam.
      Onze passie is het creëren van fraaie, toegankelijke websites en webapplicaties.
      Websites worden gebouwd volgens de webstandaarden van <a title="Website van het World Wide Web Consortium" target="_blank" href="http://www.w3.org/">W3C</a> en de <a title="Het kwaliteitsmodel Webrichtlijnen: een duurzaam toegankelijk web voor iedereen. Op webrichtlijnen.nl" target="_blank" href="http://www.webrichtlijnen.nl/">webrichtlijnen van de overheid</a>.
      Wij werken persoonlijk, professioneel en snel.
    </p><p>
      Klanten werken graag met ons samen, want:
    </p><ul>
      <li>
        <b>Wij besparen tijd en geld.</b>
        Waar mogelijk gebruiken wij bestaande code en passen wij deze aan uw wensen aan.
        Dit bespaart ons tijd en dat ziet u zodoende direct terug in het projectbudget.<br><br>
      </li>
      <li>
        <b>Wij focussen op innovatie.</b>
        Doordat wij bouwen op bestaande code, kunnen wij constant innoveren.
        Dit resulteert in betere websites met de nieuwste webtechnologieën.<br><br>
      </li>
      <li>
        <b>Wij zijn persoonlijk en betrokken.</b>
        Wij geloven in sterke, open communicatie.
        U heeft bij Tuxion daarom één contactpersoon die al uw vragen beantwoordt en uw opdrachten snel en deskundig afhandelt.
        Ook na de oplevering van uw project kunt u rekenen op onze uitstekende dienstverlening. 
      </li>
    </ul>
  </section>

  <section id="services" class="item blue solid">
    <header><h1>Diensten</h1></header>

    <h3>Websites volgens webstandaarden</h3>

    <p>
      Onze websites zijn herkenbaar aan sprekende designs en codering volgens webstandaarden van het <a href="http://www.w3.org/" target="_blank" title="Website van het World Wide Web Consortium">W3C</a>.
    </p>

    <h3>Webapplicaties die werken</h3>

    <p>
      Dagelijks werken wij aan interessante webapplicaties, die gebruiksvriendelijk zijn en perfect werken.
      U kunt hierbij denken aan boekhoudsoftware, voorraadsystemen, software in bibliotheken en <a href="http://www.rijksoverheid.nl/onderwerpen/elektronisch-patientendossier#ref-minvws" target="_blank" title="Elektronisch pati&euml;ntendossier">EPD</a>-infrastructuur.
      Het gehele proces wordt door ons verzorgd: van het uitwerken van een concept tot de technische realisatie daarvan.
      Dit geeft u het grote voordeel dat techniek en ontwerp naadloos in elkaar overvloeien.
      Daarnaast zijn wij in elke fase direct aanspreekbaar, waardoor wij snel kunnen inspelen op uw specifieke wensen.
    </p>

    <h3>Beheer</h3>

    <p>
      Wenst u een site-update? Geen probleem, wij helpen u graag.
      Stuur uw wensen naar ons op en wij zorgen er voor dat uw website up-to-date blijft.
      Zodat u zich volledig kunt richten op wat voor u belangrijk is.
    </p>

  </section>
  <section id="contact" class="item blue solid">
    <header><h1>Contact</h1></header>

    <h4>Laat een bericht achter!</h4>

    <p>
      We maken graag een afspraak voor een ori&euml;nterend gesprek, bij u of bij ons.
    </p>

    <div class="message-sent">
      Dank voor uw bericht. We nemen binnen een werkdag contact met u op.
    </div>

    <form id="contact-form" action="<?php echo url('action=tuxion/send_mail/post', true); ?>" method="post" target="_self">
      <input type="text" title="Uw naam" placeholder="Uw naam" name="name" />
      <input type="text" title="Bedrijfsnaam" placeholder="Bedrijfsnaam" name="company" />
      <div class="group">
        <input type="text" title="E-mailadres" placeholder="E-mailadres" name="email" class="small-field" />
        <input type="tel" title="Telefoonnummer" placeholder="Telefoon" name="phone" class="small-field" />
      </div>
      <input type="text" title="Onderwerp" placeholder="Onderwerp" data-default-value="Just saying hello" name="subject" />
      <textarea name="message" title="Uw bericht" placeholder="Uw bericht"></textarea>
      <input type="submit" value="Verzend bericht"  class="button" />
    </form>

    <p>
      U kunt ons ook altijd een e-mail sturen op <a href="mailto:team@tuxion.nl">team@tuxion.nl</a>, of bel <nobr><a href="tel:+31108406776" title="Klik om ons te bellen via uw VoIP-applicatie" class="no-underline" target="_self">010 - 840 67 76</a></nobr>.
    </p>

    <br />

    <div class="group">
  
      <p>
        <b>Postadres</b><br />
        Rusthofstraat 63<br />
        3034 TR  Rotterdam
      </p>

      <p class="it-would-be-great-to-meet-you">
        <b>Bezoekadres</b><br />
        Slinge 250<br />
        3085 EX  Rotterdam
      </p>

    </div>

    <div class="group">

      <p>
        <b>KvK-nummer</b><br />
        24371485
      </p>

      <p>
        <b>Rekeningnummer</b><br />
        ING 5306733
      </p>

    </div>

  </section>
</div>

<div class="sidebar-menu">
  
  <a href="#team" class="menu-item team">Het team</a>
  <a href="#about" class="menu-item over">Over Tuxion</a>
  <a href="#services" class="menu-item diensten">Diensten</a>
  <a href="#contact" class="menu-item contact">Contact</a>
  
</div>
