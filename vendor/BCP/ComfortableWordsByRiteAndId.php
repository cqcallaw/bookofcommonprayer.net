<?php
namespace BCP;

use Exception;
use PDO;

class ComfortableWordsByRiteAndId
{
    public $which_prayer    = null;
    public $which_rite      = null;
    public $do_id           = null;
    public $cw              = null;

    public function __construct($wp, $wr, $id)
    {
        $this->which_prayer = $wp;
        $this->which_rite = $wr;
        $this->do_id = $id;
        self::getComfortableWords();
    }
    /**
     * getComfortableWords - Retrieves the information based on an array of
     * comfortable_words that are listed according to daily office ids.
     */
    public function getComfortableWords()
    {
        if ($this->which_prayer == "morning_prayer") {
            if ($this->which_rite == "rite_i") {
              // COMFORTABLE WORDS
                if (($this->do_id == "1A")
                || ($this->do_id == "2A")
                || ($this->do_id == "3A")
                || ($this->do_id == "4A")) {
                    $which = rand(0, 2);
                    $a_cw[0] = "Watch ye , for ye know not when the master of the house cometh, at even, or at
                          midnight, or at the cock-crowing, or in the morning; lest coming suddenly he find
                          you sleeping.<br> <em>Mark 13:35, 36</em>";
                      $a_cw[1] = "Prepare ye the way of the Lord, make straight in the desert a highway for our
                              God.<br>  <em>Isaiah 40:3</em>";
                      $a_cw[2] = "The glory of the Lord shall be revealed, and all flesh shall see it
                              together.<br>  <em>Isaiah 40:5</em>";
                      $this->cw = $a_cw[$which];
                }
                if (($this->do_id == "CM") || ($this->do_id == "CMII") || ($this->do_id == "CMIII")) {
                    $which = rand(0, 1);
                    $cm_cw[0] = "Behold, I bring you good tidings of a great joy, which shall be to all people.
                          For unto you is born this day in the city of David a Savior, which is Christ the
                          Lord.<br>  <em>Luke 2:10, 11</em>";
                      $cm_cw[1] = "Behold, the tabernacle of God is with men, and he will dwell with them, and
                              they shall be his people, and God himself shall be with them, and be their God.<br>
                                <em>Revelation 21:3</em>";
                      $this->cw = $cm_cw[$which];
                }
                if (($this->do_id == "1E") || ($this->do_id == "2E") || ($this->do_id == "3E")
                || ($this->do_id == "4E") || ($this->do_id == "5E") || ($this->do_id == "6E")
                || ($this->do_id == "7E") || ($this->do_id == "8E") ||
                  ($this->do_id == "LE")) {
                      $which = rand(0, 2);
                      $e_cw[0] = "The Gentiles shall come to thy light, and kings to the brightness of thy rising.
                              <br>  <em>Isaiah 60:3</em>";
                      $e_cw[1] = "I will give thee for a light to the Gentiles, that thou mayest be my
                              salvation unto the end of the earth.<br>  <em>Isaiah 49:6b</em>";
                      $e_cw[2] = "From the rising of the sun even unto the going down of the same my Name shall
                              be great among the Gentiles, and in every place incense shall be offered unto
                              my Name, and a pure offering: for my Name shall be great among the heathen,
                              saith the Lord of hosts.<br>  <em>Malachi 1:11</em>";
                      $this->cw = $e_cw[$which];
                }
                if (($this->do_id == "1L") || ($this->do_id == "2L") || ($this->do_id == "3L") ||
                  ($this->do_id == "4L") || ($this->do_id == "5L")) {
                      $which = rand(0, 4);
                      $l_cw[0] = "If we say we have no sin, we deceive ourselves, and the
              truth is not in us, but if we confess our sins, God is faithful
              and just to forgive us our sins, and to cleanse us from all
              unrighteousness.<br>  <em>1 John 1:8, 9</em> ";
                      $l_cw[1] = "Rend your heart, and not your garments, and turn unto the
              Lord your God; for he is gracious and merciful, slow to
              anger and of great kindness, and repenteth him of evil.<br>
              <em>Joel 2:13</em>";
                      $l_cw[2] = "I will arise and go to my father, and will say unto him,
              'Father, I have sinned against heaven, and before thee; and
              am no more worthy to be called thy son.'<br>  <em>Luke 15:18, 19</em>";
                      $l_cw[3] = "To the Lord our God belong mercies and forgivenesses,
              though we have rebelled against him; neither have we obeyed
              the voice of the Lord our God, to walk in his laws which he
              set before us.<br>  <em>Daniel 9:9, 10</em>";
                      $l_cw[4] = "Jesus said, 'Whosoever will come after me, let him deny
              himself, and take up his cross, and follow me.'<br>  <em>Mark 8:34</em>";
                      $this->cw = $l_cw[$which];
                }
                if ($this->do_id == "PS") {
                    $which = rand(0, 1);
                    $ps_cw[0] = "All we like sheep have gone astray; we have turned every one
          to his own way; and the Lord hath laid on him the iniquity of
          us all.<br><em>Isaiah 53:6</em>";
                    $ps_cw[1] = "Is it nothing to you, all ye that pass by?  Behold and see if
          there be any sorrow like unto my sorrow which is done unto
          me, wherewith the Lord hath afflicted me. <br><em>Lamentations 1:12</em>";
                    $this->cw = $ps_cw[$which];
                }
                if (($this->do_id == "R") || ($this->do_id == "2R") || ($this->do_id == "3R") || ($this->do_id == "4R")
                || ($this->do_id == "5R") || ($this->do_id == "6R") || ($this->do_id == "7R")
                || ($this->do_id == "P") || ($this->do_id == "AD")) {
                    $which = rand(0, 5);
                    $r_cw[0] = "Alleluia! Christ is risen.<br>
          <em>The Lord is risen indeed. Alleluia!</em>";
                    $r_cw[1] = "This is the day which the Lord hath made; we will rejoice and
          be glad in it.<br>  <em>Psalm 118:24</em>";
                    $r_cw[2] = "Thanks be to God, which giveth us the victory through our
          Lord Jesus Christ.<br>  <em>1 Corinthians 15:57</em>";
                    $r_cw[3] = "If ye then be risen with Christ, seek the things which are
          above, where Christ sitteth on the right hand of God.<br>
          <em>Colossians 3:1</em>";
                    $r_cw[4] = "Christ is not entered into the holy places made with hands,
          which are the figures of the true; but into heaven itself, now
          to appear in the presence of God for us.<br>  <em>Hebrews 9:24</em>";
                    $r_cw[5] = "Ye shall receive power after the Holy Ghost is come
          upon you; and ye shall be witness unto me both in
          Jerusalem, and in all Judaea, and in Samaria, and unto the
          uttermost part of the earth.<br>  <em>Acts 1:8</em>";
                    $this->cw = $r_cw[$which];
                }
                if ($this->do_id == "T") {
                     // T
                    $which = rand(0, 2);
                    $t_cw[0] = "Holy, holy, holy, Lord God us meet to
          be partakers of the inheritance of the saints in light.<br>
          <em>Colossians 1:12</em>";
                    $t_cw[1] = "Ye are no more strangers and foreigners, but fellow-citizens
          with the saints and of the household of God.<br>  <em>Ephesians 2:19</em>";
                    $t_cw[2] = "Their sound is gone out into all lands, and their words into
          the ends of the world.<br>  <em>Psalm 19:4</em>";
                    $this->cw = $t_cw[$which];
                }
                $pattern = "/^[0-9]{0,2}$/";//matches for 1-2 digits (and digits only)
                // from start of line to end of subject
                $is_proper = preg_match($pattern, $this->do_id);
                if ($is_proper) {
                    // at any time
                    $which = rand(0, 6);
                    $p_cw[0] = "Grace be unto you, and peace, from God our Father, and
          from the Lord Jesus Christ.  <em>Phillipians 1:2</em>";
                    $p_cw[1] = "I was glad when they said unto me, 'We will go into the
          house of the Lord.'  <em>Psalm 122:1</em>";
                    $p_cw[2] = "Let the words of my mouth, and the meditation of my heart,
          be always acceptable in thy sight, O Lord, my strength and
          my redeemer.  <em>Psalm 19:14</em>";
                    $p_cw[3] = "O send out thy light and thy truth, that they may lead me,
          and bring me unto thy holy hill, and to thy dwelling.  <em>Psalm 43:3</em>";
                    $p_cw[4] = "The Lord is in his holy temple; let all the earth keep silence
          before him.  <em>Habakkuk 2:20</em>";
                    $p_cw[5] = "The hour cometh, and now is, when the true worshipers
          shall worship the Father in spirit and in truth, for the Father
          seeketh such to worship him.  <em>John 4:23</em>";
                    $p_cw[6] = array("cw"=>"Thus saith the high and lofty One who inhabiteth eternity,
          whose name is Holy, 'I dwell in the high and holy place, with
          him also that is of a contrite and humble spirit, to revive the
          spirit of the humble, and to revive the heart of the contrite
          ones.'","reference"=>"<em>Isaiah 57:15</em>");
                    $this->cw = $p_cw[$which];
                }
                // End of Rite I
            } elseif ($this->which_rite == "rite_ii") {
                if (($this->do_id == "1A") || ($this->do_id == "2A") || ($this->do_id == "3A") ||
                    ($this->do_id == "4A")) {// so 1A, 2A, 3A, or 4A
                        $which = rand(0, 2);
                        $a_cw[0] = "Watch, for you do not know when the master of the house
        will come, in the evening, or at midnight, or at cockcrow, or
        in the morning, lest he come suddenly and find you asleep.<br> <em>Mark 13:35, 36</em>";
                        $a_cw[1] = "In the wilderness prepare the way of the Lord, make straight
        in the desert a highway for our God.<br>  <em>Isaiah 40:3</em>";
                        $a_cw[2] = "The glory of the Lord shall be revealed, and all flesh shall see
        it together.<br>  <em>Isaiah 40:5</em>";
                        $this->cw = $a_cw[$which];
                }
                if (($this->do_id == "CM") || ($this->do_id == "CMII") || ($this->do_id == "CMIII")) {
                    // cm, cmii, cmiii
                    $which = rand(0, 1);
                    $cm_cw[0] = "Behold, I bring you good news of a great joy which will come
        to all the people; for to you is born this day in the city of
        David, a Savior, who is Christ the Lord.<br>  <em>Luke 2:10, 11</em>";
                    $cm_cw[1] = "Behold, the dwelling of God is with mankind.  He will dwell
        with them, and they shall be his people, and God himself will
        be with them, and be their God.<br>  <em>Revelation 21:3</em>";
                    $this->cw = $cm_cw[$which];
                }
                if (($this->do_id == "1E") || ($this->do_id == "2E") || ($this->do_id == "3E")
                || ($this->do_id == "4E") || ($this->do_id == "5E") || ($this->do_id == "6E")
                || ($this->do_id == "7E") || ($this->do_id == "8E") || ($this->do_id == "LE")) {
                        // 1E - LE
                        $which = rand(0, 2);
                        $e_cw[0] = "Nations shall come to your light, and kings to the brightness
        of your rising.<br>  <em>Isaiah 60:3</em>";
                        $e_cw[1] = "I will give you as a light to the nations, that my salvation may
        reach to the end of the earth.<br>  <em>Isaiah 49:6b</em>";
                        $e_cw[2] = "From the rising of the sun to its setting my Name shall be great
        among the nations, and in every place incense shall be offered
        to my Name, and a pure offering; for my Name shall be great
        among the nations, says the Lord of hosts.<br>  <em>Malachi 1:11</em>";
                        $this->cw = $e_cw[$which];
                }
                if (($this->do_id == "1L") || ($this->do_id == "2L") || ($this->do_id == "3L")
                || ($this->do_id == "4L") || ($this->do_id == "5L")) {
                        // 1L - 5L
                        $which = rand(0, 4);
                        $l_cw[0] = "If we say we have no sin, we deceive ourselves, and the truth
        is not in us, but if we confess our sins, God, who is faithful
        and just, will forgive our sins and cleanse us from all
        unrighteousness.<br>  <em>1 John 1:8, 9</em> ";
                        $l_cw[1] = "Rend your hearts and not your garments.  Return to the Lord
        your God, for he is gracious and merciful, slow to anger and
        abounding in steadfast love, and repents of evil.<br>
            <em>Joel 2:13</em>";
                        $l_cw[2] = "I will arise and go to my father, and I will say to him,
        'Father, I have sinned against heaven and before you; I am
        no longer worthy to be called your son.'<br>  <em>Luke 15:18, 19</em>";
                        $l_cw[3] = "To the Lord our God belong mercy and forgiveness, because we
                        have rebelled against him and have not obeyed the voice of the Lord our
                        God by following his laws which he set before us.<br>  <em>Daniel 9:9,
                        10</em>";
                        $l_cw[4] = "Jesus said, 'If anyone would come after me, let him deny
                        himself and take up his cross and follow me.'<br>  <em>Mark 8:34</em>";
                        $this->cw = $l_cw[$which];
                }
                if ($this->do_id == "PS") {
                        // PS
                        $which = rand(0, 1);
                        $ps_cw[0] = "All we like sheep have gone astray; we have turned every one
            to his own way; and the Lord has laid on him the iniquity of
            us all.<br><em>Isaiah 53:6</em>";
                        $ps_cw[1] = "Is it nothing to you, all you who pass by?  Look and see if
            there is any sorrow like my sorrow which was brought upon
            me, whom the Lord has afflicted. <br><em>Lamentations 1:12</em>";
                        $this->cw = $ps_cw[$which];
                }
                if (($this->do_id == "R") || ($this->do_id == "2R") || ($this->do_id == "3R")
                || ($this->do_id == "4R") || ($this->do_id == "5R") || ($this->do_id == "6R")
                || ($this->do_id == "7R") || ($this->do_id == "P") || ($this->do_id == "AD")) {
                    // R - 7R, P, and AD
                    $which = rand(0, 5);
                    $r_cw[0] = "Alleluia! Christ is risen.<br>
            <em>The Lord is risen indeed. Alleluia!</em>";
                    $r_cw[1] = "On this day the Lord has acted; we will rejoice and be glad in
        it.<br>  <em>Psalm 118:24</em>";
                    $r_cw[2] = "Thanks be to God, who gives us the victory through our Lord
        Jesus Christ.<br>  <em>1 Corinthians 15:57</em>";
                    $r_cw[3] = "If then you have been raised with Christ, seek the things that
        are above, where Christ is, seated at the right hand of God.<br>
            <em>Colossians 3:1</em>";
                    $r_cw[4] = "Christ has entered, not into a sanctuary made with hands, a
        copy of the true one, but into heaven itself, now to appear in
        the presence of God on our behalf.<br>  <em>Hebrews 9:24</em>";
                    $r_cw[5] = "You shall receive power when the Holy Spirit has come upon
        you; and you shall be my witness in Jerusalem, and in all
        Judea, and Samaria, and to the ends of the earth.<br>  <em>Acts 1:8</em>";
                    $this->cw = $r_cw[$which];
                }
                if ($this->do_id == "T") {
                    // T
                    $which = rand(0, 2);
                    $t_cw[0] = "Holy, holy, holy is the Lord God Almighty, who was, and is,
        and is to come!<br>
            <em>Colossians 1:12</em>";
                    $this->cw = $t_cw[$which];
                }
                $pattern = "/^[0-9]{0,2}$/";//matches for 1-2 digits (and digits only) from start of line to end
                     //of subject
                $is_proper = preg_match($pattern, $this->do_id);
                if ($is_proper) {
                    // at any time
                    $which = rand(0, 6);
                    $p_cw[0] = "Grace to you and peace from God our Father and the Lord
        Jesus Christ.  <em>Phillipians 1:2</em>";
                    $p_cw[1] = "I was glad when they said to me, 'Let us go to the house of
        the Lord.'  <em>Psalm 122:1</em>";
                    $p_cw[2] = "Let the words of my mouth and the meditation of my heart
        be acceptable in your sight, O Lord, my strength and my
        redeemer.  <em>Psalm 19:14</em>";
                    $p_cw[3] = "Send out your light and your truth, that they may lead me,
        and bring me to your holy hill and to your dwelling.  <em>Psalm 43:3</em>";
                    $p_cw[4] = "The Lord is in his holy temple; let all the earth keep silence
        before him.  <em>Habakkuk 2:20</em>";
                    $p_cw[5] = "The hour is coming, and now is, when the true worshipers
        will worship the Father in spirit and truth, for such the
        Father seeks to worship him.  <em>John 4:23</em>";
                    $p_cw[6] = "Thus says the high and lofty One who inhabits eternity,
        whose name is Holy, 'I dwell in the high and holy place and
        also with the one who has a contrite and humble spirit, to
        revive the spirit of the humble and to revive the heart of the
        contrite.'  <em>Isaiah 57:15</em>";
                    $this->cw = $p_cw[$which];
                }
        // End of Rite II
            }
      // End of Morning Prayer
        } elseif ($this->which_prayer == "evening_prayer") {
            if ($this->which_rite == "rite_i") {
                // End of Rite I
            } elseif ($this->which_rite == "rite_ii") {

                // End of Rite II
            }
            // End of Evening Prayer
        }
        // end of comfortable word method
    }
}
