<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        $data = $this->getData();
        $lines = explode('|', $data);

        $contestants = [];
        $groups = [];
        $events = [];
        $competitions = [];
        $entries = [];

        foreach($lines as $line)
        {
            $columns = explode(',', $line);
            
            $lastName = trim($columns[0]);
            $firstName = trim($columns[1]);
            $group = trim($columns[2]);
            $event = trim($columns[3]);

            // contestants
            $key = "{$lastName},{$firstName}";

            $contestants[$key] = [
                'last' => $lastName,
                'first' => $firstName
            ];

            // groups
            if( !in_array($group, $groups) ) 
            {
                $groups[] = $group;
            }

            // events 
            if( !in_array($event, $events) )
            {
                $events[] = $event;
            }

            // competitions 
            $key = "{$group}-{$event}";
            $competitions[$key] = [
                'group' => $group,
                'event' => $event
            ];
            
            // entries
            $entries[] = [
                'last' => $lastName,
                'first' => $firstName,
                'group' => $group,
                'event' => $event,
            ];
        }

        $rodeoId = $this->getRodeo();
        if( null == $rodeoId )
        {
            return null;
        }

        $rodeo = \App\Rodeo::findOrFail($rodeoId);

        $orgGroupNames = $rodeo->organization->groups->pluck('name')->toArray();

        foreach($groups as $group)
        {
            if( !in_array($group, $orgGroupNames) )
            {
                $this->error("$group does not exist...");
            }
        }


    }

    public function getRodeo()
    {
        $rodeos = \App\Rodeo::all();

        $ids = [];
        foreach($rodeos as $rodeo)
        {
            $this->line("  {$rodeo->id} : {$rodeo->starts_at} : $rodeo->name - Org: $rodeo->organization_id ");
            $ids[] = $rodeo->id;
        }

        $id = null;
        while (!in_array($id, $ids)) 
        {
            $id = $this->ask("Rodeo?");

            if( in_array($id, ['q', 'Q', 'quit', 'QUIT', 'exit']) )
            {
                return null;
            }
        }

        return $id;
    }

    protected function getData()
    {
        return 'ABERNATHY, LOLA KAY,PEE WEE,POLES
        |
        BERGSTROM, CHARLIE ANNE,PEE WEE,POLES
        |
        BLEVINS, OAKLEY,PEE WEE,POLES
        |
        BRANTLEY, TALON,PEE WEE,POLES
        |
        BUTLER, HARPER,PEE WEE,POLES
        |
        CLAWSON, CASSIE,PEE WEE,POLES
        |
        COOPER, RAWSEN,PEE WEE,POLES
        |
        COOPER, RYEKER,PEE WEE,POLES
        |
        COX, JACE,PEE WEE,POLES
        |
        CRENSHAW, ROWDY,PEE WEE,POLES
        |
        GLENN, DALLIE,PEE WEE,POLES
        |
        HATANVILLE, LAKYN,PEE WEE,POLES
        |
        HODGES, MERCY,PEE WEE,POLES
        |
        HODGES, WESLEY,PEE WEE,POLES
        |
        HOUSTON, PEPPER,PEE WEE,POLES
        |
        HOUSTON, PISTOL,PEE WEE,POLES
        |
        JORDAN, ABELLA,PEE WEE,POLES
        |
        MARILUCH, MADDIC,PEE WEE,POLES
        |
        PHILLIPS, LYNNLEE,PEE WEE,POLES
        |
        PITCOCK, COLT,PEE WEE,POLES
        |
        SHOWERS, ALLISON,PEE WEE,POLES
        |
        STOUT, SWAYZE,PEE WEE,POLES
        |
        THARP, LEXIE,PEE WEE,POLES
        |
        VAUGHN, BLAKELY,PEE WEE,POLES
        |
        WARREN, BRISTOL,PEE WEE,POLES
        |
        WILLIAMS, RYDER,PEE WEE,POLES
        |
        YOUNG, GAGE,PEE WEE,POLES
        |
        BROWN, RANCE,PEE WEE,POLES
        |
        HODGES, TEXI BELLE,PEE WEE,POLES
        |
        PERKINS, BLEU,PEE WEE,POLES
        |
        SCHLUSEMEYER, GRACELYN,PEE WEE,POLES
        |
        TUTTLE, CAMRYN,PEE WEE,POLES
        |
        VEASLEY, LIVI,PEE WEE,POLES
        |
        BARNETT, BRITTAN,PEE WEE,POLES
        |
        BAILEY, BRYNLEE,PEE WEE,POLES
        |
        BAXTER, MCKAMIE,PEE WEE,POLES
        |
        CONLEY, CAYDEN,PEE WEE,POLES
        |
        FOWLER, PAISLEY,PEE WEE,POLES
        |
        JONES, JOPLYNN,PEE WEE,POLES
        |
        JONES, LAYCN,PEE WEE,POLES
        |
        MCCARTY, CUTTER,PEE WEE,POLES
        |
        MCMINN, KAYDENCE,PEE WEE,POLES
        |
        NIEWIADOMSKI, AVA,PEE WEE,POLES
        |
        NIEWIADOMSKI, NOLAN,PEE WEE,POLES
        |
        ROMANS, TAYLOR,PEE WEE,POLES
        |
        SWANN, CHLOE,PEE WEE,POLES
        |
        ABERNATHY, LOLA KAY,PEE WEE,BARRELS
        |
        BERGSTROM, CHARLIE ANNE,PEE WEE,BARRELS
        |
        BLEVINS, OAKLEY,PEE WEE,BARRELS
        |
        BUTLER, HARPER,PEE WEE,BARRELS
        |
        CLAWSON, CASSIE,PEE WEE,BARRELS
        |
        CLINARD, SAYLOR,PEE WEE,BARRELS
        |
        CLINARD, SCOUT,PEE WEE,BARRELS
        |
        COFFEY, JASPER,PEE WEE,BARRELS
        |
        COOPER, RAWSEN,PEE WEE,BARRELS
        |
        COOPER, RYEKER,PEE WEE,BARRELS
        |
        COX, JACE,PEE WEE,BARRELS
        |
        CRENSHAW, ROWDY,PEE WEE,BARRELS
        |
        DENNIE, CHET,PEE WEE,BARRELS
        |
        GARRETT, ADALYNN,PEE WEE,BARRELS
        |
        GLENN, DALLIE,PEE WEE,BARRELS
        |
        GRACE, TINLEY,PEE WEE,BARRELS
        |
        HATANVILLE, LAKYN,PEE WEE,BARRELS
        |
        HODGES, MERCY,PEE WEE,BARRELS
        |
        HODGES, TEXI BELLE,PEE WEE,BARRELS
        |
        HODGES, WESLEY,PEE WEE,BARRELS
        |
        HOOTEN, HEIDI,PEE WEE,BARRELS
        |
        HOOTEN, JUDSON,PEE WEE,BARRELS
        |
        HOUSTON, PEPPER,PEE WEE,BARRELS
        |
        HOUSTON, PISTOL,PEE WEE,BARRELS
        |
        JACKSON, HARPER,PEE WEE,BARRELS
        |
        LITTRELL, LYNLEE,PEE WEE,BARRELS
        |
        MARILUCH, MADDIC,PEE WEE,BARRELS
        |
        PHILLIPS, LYNNLEE,PEE WEE,BARRELS
        |
        PITCOCK, COLT,PEE WEE,BARRELS
        |
        SELF, ANDIE,PEE WEE,BARRELS
        |
        SELF, MACIE,PEE WEE,BARRELS
        |
        SHOWERS, ALLISON,PEE WEE,BARRELS
        |
        SMITH, VIVIE,PEE WEE,BARRELS
        |
        STOUT, SWAYZE,PEE WEE,BARRELS
        |
        THARP, LEXIE,PEE WEE,BARRELS
        |
        VAUGHN, BLAKELY,PEE WEE,BARRELS
        |
        WARREN, BRISTOL,PEE WEE,BARRELS
        |
        WETTELAND, SADIE,PEE WEE,BARRELS
        |
        WILLIAMS, RYDER,PEE WEE,BARRELS
        |
        WRIGHT, HARLEE,PEE WEE,BARRELS
        |
        YOUNG, GAGE,PEE WEE,BARRELS
        |
        BROWN, RANCE,PEE WEE,BARRELS
        |
        FIGUEROA, TUCKER,PEE WEE,BARRELS
        |
        PERKINS, BLEU,PEE WEE,BARRELS
        |
        SCHLUSEMEYER, GRACELYN,PEE WEE,BARRELS
        |
        TUTTLE, CAMRYN,PEE WEE,BARRELS
        |
        VEASLEY, LIVI,PEE WEE,BARRELS
        |
        BARNETT, BRITTAN,PEE WEE,BARRELS
        |
        BAILEY, BRYNLEE,PEE WEE,BARRELS
        |
        BAXTER, MCKAMIE,PEE WEE,BARRELS
        |
        BROCK, PAISLEY JO,PEE WEE,BARRELS
        |
        JONES, JOPLYNN,PEE WEE,BARRELS
        |
        JONES, LAYCN,PEE WEE,BARRELS
        |
        MCCARTY, CUTTER,PEE WEE,BARRELS
        |
        MCMINN, KAYDENCE,PEE WEE,BARRELS
        |
        PHIFER, COY,PEE WEE,BARRELS
        |
        PHIFER, RYLIE,PEE WEE,BARRELS
        |
        SWANN, CHLOE,PEE WEE,BARRELS
        |
        ABERNATHY, LOLA KAY,PEE WEE,GOAT RIBBON PULL
        |
        BERGSTROM, CHARLIE ANNE,PEE WEE,GOAT RIBBON PULL
        |
        BLEVINS, OAKLEY,PEE WEE,GOAT RIBBON PULL
        |
        BURGIN, LANDON,PEE WEE,GOAT RIBBON PULL
        |
        BUTLER, HARPER,PEE WEE,GOAT RIBBON PULL
        |
        CLAWSON, CASSIE,PEE WEE,GOAT RIBBON PULL
        |
        CLINARD, SAYLOR,PEE WEE,GOAT RIBBON PULL
        |
        CLAWSON, CAYLEIGH,PEE WEE,GOAT RIBBON PULL
        |
        CLINARD, SCOUT,PEE WEE,GOAT RIBBON PULL
        |
        COOPER, RAWSEN,PEE WEE,GOAT RIBBON PULL
        |
        COOPER, RYEKER,PEE WEE,GOAT RIBBON PULL
        |
        COX, JACE,PEE WEE,GOAT RIBBON PULL
        |
        DOMINGUE, TRISTAN,PEE WEE,GOAT RIBBON PULL
        |
        DOMINGUE, TRUE,PEE WEE,GOAT RIBBON PULL
        |
        FRAZIER, WAYLON,PEE WEE,GOAT RIBBON PULL
        |
        GARRETT, ADALYNN,PEE WEE,GOAT RIBBON PULL
        |
        GLENN, DALLIE,PEE WEE,GOAT RIBBON PULL
        |
        GRACE, TINLEY,PEE WEE,GOAT RIBBON PULL
        |
        HATANVILLE, LAKYN,PEE WEE,GOAT RIBBON PULL
        |
        HODGES, TEXI BELLE,PEE WEE,GOAT RIBBON PULL
        |
        HODGES, WESLEY,PEE WEE,GOAT RIBBON PULL
        |
        HOOTEN, HEIDI,PEE WEE,GOAT RIBBON PULL
        |
        HOOTEN, JUDSON,PEE WEE,GOAT RIBBON PULL
        |
        HOUSTON, GRANT,PEE WEE,GOAT RIBBON PULL
        |
        HOUSTON, PEPPER,PEE WEE,GOAT RIBBON PULL
        |
        HOUSTON, PISTOL,PEE WEE,GOAT RIBBON PULL
        |
        JACKSON, HARPER,PEE WEE,GOAT RIBBON PULL
        |
        LUMMUS, CLANCY,PEE WEE,GOAT RIBBON PULL
        |
        LUMMUS, DODGE,PEE WEE,GOAT RIBBON PULL
        |
        LUMMUS, HATTLIE,PEE WEE,GOAT RIBBON PULL
        |
        MARILUCH, MADDIC,PEE WEE,GOAT RIBBON PULL
        |
        MORTON, JACKSON,PEE WEE,GOAT RIBBON PULL
        |
        PHILLIPS, LYNNLEE,PEE WEE,GOAT RIBBON PULL
        |
        PITCOCK, COLT,PEE WEE,GOAT RIBBON PULL
        |
        PRIEFERT, PRESLEE,PEE WEE,GOAT RIBBON PULL
        |
        SELF, ANDIE,PEE WEE,GOAT RIBBON PULL
        |
        SELF, MACIE,PEE WEE,GOAT RIBBON PULL
        |
        SHOWERS, ALLISON,PEE WEE,GOAT RIBBON PULL
        |
        SWANSON, BRAYDEN,PEE WEE,GOAT RIBBON PULL
        |
        TORRES, JAVONNI,PEE WEE,GOAT RIBBON PULL
        |
        TORRES, OLIVIA,PEE WEE,GOAT RIBBON PULL
        |
        VALENZUELA, JAD,PEE WEE,GOAT RIBBON PULL
        |
        VAUGHN, BLAKELY,PEE WEE,GOAT RIBBON PULL
        |
        VEASLEY, LIVI,PEE WEE,GOAT RIBBON PULL
        |
        WARREN, BRISTOL,PEE WEE,GOAT RIBBON PULL
        |
        WEBER, JAX,PEE WEE,GOAT RIBBON PULL
        |
        WETTELAND, SADIE,PEE WEE,GOAT RIBBON PULL
        |
        WILLIAMS, HARPER,PEE WEE,GOAT RIBBON PULL
        |
        YOUNG, GAGE,PEE WEE,GOAT RIBBON PULL
        |
        FLOWERS, AUSTIN,PEE WEE,GOAT RIBBON PULL
        |
        PERKINS, BLEU,PEE WEE,GOAT RIBBON PULL
        |
        TUTTLE, CAMRYN,PEE WEE,GOAT RIBBON PULL
        |
        WALLACE, CAMDEN,PEE WEE,GOAT RIBBON PULL
        |
        BARNETT, BRITTAN,PEE WEE,GOAT RIBBON PULL
        |
        TULLY, PAXTON,PEE WEE,GOAT RIBBON PULL
        |
        BAXTER, MCKAMIE,PEE WEE,GOAT RIBBON PULL
        |
        DUFRENE, BREANNA,PEE WEE,GOAT RIBBON PULL
        |
        JONES, JOPLYNN,PEE WEE,GOAT RIBBON PULL
        |
        JONES, LAYCN,PEE WEE,GOAT RIBBON PULL
        |
        MCCARTY, CUTTER,PEE WEE,GOAT RIBBON PULL
        |
        MORGAN, DALLYN,PEE WEE,GOAT RIBBON PULL
        |
        PHIFER, COY,PEE WEE,GOAT RIBBON PULL
        |
        PHIFER, RYLIE,PEE WEE,GOAT RIBBON PULL
        |
        RAMIREZ, DANIEL,PEE WEE,GOAT RIBBON PULL
        |
        RAMIREZ, NATALIE,PEE WEE,GOAT RIBBON PULL
        |
        ALDERMAN, CAMDEN,PEE WEE,MUTTON BUSTIN
        |
        ALDERMAN, CREW,PEE WEE,MUTTON BUSTIN
        |
        COX, JACE,PEE WEE,MUTTON BUSTIN
        |
        DAVIS, JESSE,PEE WEE,MUTTON BUSTIN
        |
        FRAZIER, WAYLON,PEE WEE,MUTTON BUSTIN
        |
        HOUSTON, GRANT,PEE WEE,MUTTON BUSTIN
        |
        LABOVE, ASHTON,PEE WEE,MUTTON BUSTIN
        |
        LILLEY, LUCAS,PEE WEE,MUTTON BUSTIN
        |
        LILLEY, OWEN,PEE WEE,MUTTON BUSTIN
        |
        MORGAN, DALLYN,PEE WEE,MUTTON BUSTIN
        |
        MORTON, JACKSON,PEE WEE,MUTTON BUSTIN
        |
        POGUE, BRYAR,PEE WEE,MUTTON BUSTIN
        |
        SARGENT, KASON,PEE WEE,MUTTON BUSTIN
        |
        SARGENT, WAYLON,PEE WEE,MUTTON BUSTIN
        |
        SHAMSIE, WESTON,PEE WEE,MUTTON BUSTIN
        |
        SMITH, COOPER,PEE WEE,MUTTON BUSTIN
        |
        SPEIGHTS, COLTER,PEE WEE,MUTTON BUSTIN
        |
        TORRES, JAVONNI,PEE WEE,MUTTON BUSTIN
        |
        TULLY, PAXTON,PEE WEE,MUTTON BUSTIN
        |
        BROWN, RANCE,PEE WEE,MUTTON BUSTIN
        |
        CLAWSON, CAYLEIGH,PEE WEE,MUTTON BUSTIN
        |
        MILLER, FLYNT,PEE WEE,MUTTON BUSTIN
        |
        MILLER, GREY,PEE WEE,MUTTON BUSTIN
        |
        PHIPPS, TATE,PEE WEE,MUTTON BUSTIN
        |
        WALLACE, CAMDEN,PEE WEE,MUTTON BUSTIN
        |
        WATKINS, SAM,PEE WEE,MUTTON BUSTIN
        |
        BARNETT, BRITTAN,PEE WEE,MUTTON BUSTIN
        |
        WILLIAMS, MAVERICK,PEE WEE,MUTTON BUSTIN
        |
        BERGSTROM, WYATT,PEE WEE,MUTTON BUSTIN
        |
        CONLEY, CAYDEN,PEE WEE,MUTTON BUSTIN
        |
        FOWLER, PAISLEY,PEE WEE,MUTTON BUSTIN
        |
        JONES, LAYCN,PEE WEE,MUTTON BUSTIN
        |
        OLAYO, JAVI,PEE WEE,MUTTON BUSTIN
        |
        RAMIREZ, DANIEL,PEE WEE,MUTTON BUSTIN
        |
        RAMIREZ, NATALIE,PEE WEE,MUTTON BUSTIN
        |
        STRONG, BRANTLEY,PEE WEE,MUTTON BUSTIN
        |
        TERRELL, JOHN,PEE WEE,MUTTON BUSTIN
        |
        ALDERMAN, CHANDLER,7U,CALF RIDING
        |
        BERGSTROM, WYATT,7U,CALF RIDING
        |
        COOPER, SLADE,7U,CALF RIDING
        |
        FORTENBERRY, JOE,7U,CALF RIDING
        |
        SARGENT, KASON,7U,CALF RIDING
        |
        WILLIAMS, RYDER,7U,CALF RIDING
        |
        CLOUD, CAISON,7U,CALF RIDING
        |
        ALDERMAN, CAMDEN,7U,CALF RIDING
        |
        DUFRENE, BENTON,7U,CALF RIDING
        |
        ALDERMAN, CHANDLER,10U,CALF RIDING
        |
        DAVEY, RAUZEN,10U,CALF RIDING
        |
        PACE, LAYDEN,10U,CALF RIDING
        |
        QUALLS, WYATT,10U,CALF RIDING
        |
        HODGES, PIERCE,10U,CALF RIDING
        |
        JORDAN, TRENTON,10U,CALF RIDING
        |
        ALDERMAN, CAMDEN,10U,PONY BRONC
        |
        ALDERMAN, CHANDLER,10U,PONY BRONC
        |
        DARLING, STRATON,10U,PONY BRONC
        |
        MELTON, CANON,10U,PONY BRONC
        |
        PACE, LAYDEN,10U,PONY BRONC
        |
        ROMANS, WRYLEE,10U,PONY BRONC
        |
        VAUGHN, CARSON,10U,PONY BRONC
        |
        VAUGHN, CASEN,10U,PONY BRONC
        |
        CLOUD, CAISON,10U,PONY BRONC
        |
        ADAMS, ANDERSON,14U,PONY BRONC
        |
        DAVEY, RIGGEN,14U,STEER RIDING
        |
        ENGLISH, ALAN,14U,STEER RIDING
        |
        MCCRAE, KASON,14U,STEER RIDING
        |
        QUALLS, WYATT,14U,STEER RIDING
        |
        BRYANT, MIKIAH,7U,POLES
        |
        GARRETT, WYATT,7U,POLES
        |
        HODGES, DOOLEY,7U,POLES
        |
        LUMMUS, DUB,7U,POLES
        |
        MILLER, JACOB,7U,POLES
        |
        POGUE, RIVER,7U,POLES
        |
        RAWSON, BRYSON,7U,POLES
        |
        TOON, BRANTLEY,7U,POLES
        |
        WILLIAMS, MAGGIE,7U,POLES
        |
        FLOWERS, AUDREY,7U,POLES
        |
        CROW, BRYTIN,7U,POLES
        |
        PRICE, WESLEY,7U,POLES
        |
        BOULTINGHOUSE, EMMA,10U,POLES
        |
        COLLIER, RENI,10U,POLES
        |
        HODGES, HARPER,10U,POLES
        |
        HODGES, WYLIE JO,10U,POLES
        |
        MCMURROUGH, RALEIGH,10U,POLES
        |
        NATION, CHANNING,10U,POLES
        |
        SMITH, SAMANTHA,10U,POLES
        |
        TALBOTT, BREANNE,10U,POLES
        |
        TOON, SAVANNAH,10U,POLES
        |
        WEGLEY, AVA,10U,POLES
        |
        WEGLEY, EMMA,10U,POLES
        |
        CARPENTER, TYLEE,14U,POLES
        |
        CORNELL, AVERIE,14U,POLES
        |
        GILDER, SAVANNAH,14U,POLES
        |
        GOLDEN, CHESNEY,14U,POLES
        |
        JORDAN, BROOKLIN,14U,POLES
        |
        KING, MAYSUN,14U,POLES
        |
        LOGAN, LEXIE,14U,POLES
        |
        LONG, ASHLYNN,14U,POLES
        |
        PARKS, ZIGGIE,14U,POLES
        |
        SINCLAIR, AVA,14U,POLES
        |
        TURNER, KOLBY,14U,POLES
        |
        WILBURN, JIMMIE JO,14U,POLES
        |
        WILLIS, CHLOE,14U,POLES
        |
        WOOD, AVERY,14U,POLES
        |
        AKARD, ADDISON,14U,POLES
        |
        JONES, JADI,14U,POLES
        |
        MCCALLUM, RAVEN,14U,POLES
        |
        OGLESBY, BAYLI,14U,POLES
        |
        BILLS, REAGAN,19U,POLES
        |
        EASON, ALLIE,19U,POLES
        |
        GREEN, HOWARD,19U,POLES
        |
        HOWELL, AVERY,19U,POLES
        |
        BRANTLEY, TALON,7U,HORSELESS GOAT RIBBON
        |
        GARRETT, WYATT,7U,HORSELESS GOAT RIBBON
        |
        JORDAN, ABELLA,7U,HORSELESS GOAT RIBBON
        |
        LUMMUS, DUB,7U,HORSELESS GOAT RIBBON
        |
        LUMMUS, LATTIE,7U,HORSELESS GOAT RIBBON
        |
        MINTER, OCE,7U,HORSELESS GOAT RIBBON
        |
        PHILLIPS, RYDER,7U,HORSELESS GOAT RIBBON
        |
        POGUE, RIVER,7U,HORSELESS GOAT RIBBON
        |
        TOON, WESTON,7U,HORSELESS GOAT RIBBON
        |
        WILLIAMS, MAGGIE,7U,HORSELESS GOAT RIBBON
        |
        WILLIAMS, RYDER,7U,HORSELESS GOAT RIBBON
        |
        STRONG, BRANTLEY,7U,HORSELESS GOAT RIBBON
        |
        JONES, BRIGG,7U,HORSELESS GOAT RIBBON
        |
        JONES, TRIPP,7U,HORSELESS GOAT RIBBON
        |
        HODGES, DOOLEY,7U,HORSELESS GOAT RIBBON
        |
        BOULTINGHOUSE, EMMA,10U,HORSELESS GOAT RIBBON PULL
        |
        HODGES, WYLIE JO,10U,HORSELESS GOAT RIBBON PULL
        |
        JANWAY, SUSIE,10U,HORSELESS GOAT RIBBON PULL
        |
        MARLOWE, BAILEIGH,10U,HORSELESS GOAT RIBBON PULL
        |
        MCMURROUGH, RALEIGH,10U,HORSELESS GOAT RIBBON PULL
        |
        MELTON, CANON,10U,HORSELESS GOAT RIBBON PULL
        |
        SMITH, SAMANTHA,10U,HORSELESS GOAT RIBBON PULL
        |
        SWANSON, BROCK,10U,HORSELESS GOAT RIBBON PULL
        |
        SWANSON, MADISYN,10U,HORSELESS GOAT RIBBON PULL
        |
        VAUGHN, CARSON,10U,HORSELESS GOAT RIBBON PULL
        |
        WEGLEY, AVA,10U,HORSELESS GOAT RIBBON PULL
        |
        WEGLEY, EMMA,10U,HORSELESS GOAT RIBBON PULL
        |
        HODGES, DOOLEY,7U,HORSELESS GOAT TYING
        |
        LUMMUS, DUB,7U,HORSELESS GOAT TYING
        |
        LUMMUS, LATTIE,7U,HORSELESS GOAT TYING
        |
        POGUE, RIVER,7U,HORSELESS GOAT TYING
        |
        STOUT, SUTTON,7U,HORSELESS GOAT TYING
        |
        TOON, BRANTLEY,7U,HORSELESS GOAT TYING
        |
        WILLIAMS, MAGGIE,7U,HORSELESS GOAT TYING
        |
        WILLIAMS, MAVERICK,7U,HORSELESS GOAT TYING
        |
        FORTENBERRY, ADDISON,10U,HORSELESS GOAT TYING
        |
        HODGES, WYLIE JO,10U,HORSELESS GOAT TYING
        |
        MCMURROUGH, RALEIGH,10U,HORSELESS GOAT TYING
        |
        NATION, CHANNING,10U,HORSELESS GOAT TYING
        |
        NATION, JAGGER,10U,HORSELESS GOAT TYING
        |
        SMITH, SAMANTHA,10U,HORSELESS GOAT TYING
        |
        STOUT, SAWYER,10U,HORSELESS GOAT TYING
        |
        SWANSON, MADISYN,10U,HORSELESS GOAT TYING
        |
        TOON, SAVANNAH,10U,HORSELESS GOAT TYING
        |
        WEGLEY, AVA,10U,HORSELESS GOAT TYING
        |
        WEGLEY, EMMA,10U,HORSELESS GOAT TYING
        |
        HODGES, HARPER,10U,HORSELESS GOAT TYING
        |
        COLCLASURE, RILEY,14U,HORSELESS GOAT TYING
        |
        CRENSHAW, AUDREY,14U,HORSELESS GOAT TYING
        |
        JORDAN, BROOKLIN,14U,HORSELESS GOAT TYING
        |
        LOGAN, LEXIE,14U,HORSELESS GOAT TYING
        |
        EASON, ALLIE,19U,HORSELESS GOAT TYING
        |
        TREMBACK, TESSA,19U,HORSELESS GOAT TYING
        |
        BERGSTROM, WYATT,7U,GOAT RIBBON PULL
        |
        DENNIE, KOOPER,7U,GOAT RIBBON PULL
        |
        DUFRENE, BENTON,7U,GOAT RIBBON PULL
        |
        FORTENBERRY, JOE,7U,GOAT RIBBON PULL
        |
        GARRETT, WYATT,7U,GOAT RIBBON PULL
        |
        GLENN, RADLYN,7U,GOAT RIBBON PULL
        |
        HODGES, DOOLEY,7U,GOAT RIBBON PULL
        |
        JONES, BRIGG,7U,GOAT RIBBON PULL
        |
        JUAREZ, RAYLEA,7U,GOAT RIBBON PULL
        |
        LUMMUS, DODGE,7U,GOAT RIBBON PULL
        |
        LUMMUS, DUB,7U,GOAT RIBBON PULL
        |
        LUMMUS, LATTIE,7U,GOAT RIBBON PULL
        |
        MINTER, OCE,7U,GOAT RIBBON PULL
        |
        PHILLIPS, RYDER,7U,GOAT RIBBON PULL
        |
        POGUE, RIVER,7U,GOAT RIBBON PULL
        |
        RAGSDALE, HAGEN,7U,GOAT RIBBON PULL
        |
        RAWSON, BRYSON,7U,GOAT RIBBON PULL
        |
        TOON, BRANTLEY,7U,GOAT RIBBON PULL
        |
        WILLIAMS, MAGGIE,7U,GOAT RIBBON PULL
        |
        WILLIAMS, MAVERICK,7U,GOAT RIBBON PULL
        |
        WILLIAMS, RYDER,7U,GOAT RIBBON PULL
        |
        FLOWERS, AUDREY,7U,GOAT RIBBON PULL
        |
        JONES, TRIPP,7U,GOAT RIBBON PULL
        |
        TALBOTT, BESS,7U,GOAT RIBBON PULL
        |
        CROW, BRYTIN,7U,GOAT RIBBON PULL
        |
        PRICE, WESLEY,7U,GOAT RIBBON PULL
        |
        BOULTINGHOUSE, EMMA,10U,GOAT RIBBON PULL
        |
        COLLIER, RENI,10U,GOAT RIBBON PULL
        |
        DARLING, STRATON,10U,GOAT RIBBON PULL
        |
        DUFFEY, TATE,10U,GOAT RIBBON PULL
        |
        FORTENBERRY, ADDISON,10U,GOAT RIBBON PULL
        |
        HODGES, WYLIE JO,10U,GOAT RIBBON PULL
        |
        JORDAN, TRENTON,10U,GOAT RIBBON PULL
        |
        LEJEUNE, TYESON,10U,GOAT RIBBON PULL
        |
        MCMURROUGH, RALEIGH,10U,GOAT RIBBON PULL
        |
        MELTON, CANON,10U,GOAT RIBBON PULL
        |
        MINTER, HANK,10U,GOAT RIBBON PULL
        |
        ONEAL, CASH,10U,GOAT RIBBON PULL
        |
        ONEAL, LEVI,10U,GOAT RIBBON PULL
        |
        PACE, LAYDEN,10U,GOAT RIBBON PULL
        |
        PARKS, HEATH,10U,GOAT RIBBON PULL
        |
        SMITH, SAMANTHA,10U,GOAT RIBBON PULL
        |
        STOUT, SAWYER,10U,GOAT RIBBON PULL
        |
        SWANSON, BROCK,10U,GOAT RIBBON PULL
        |
        SWANSON, MADISYN,10U,GOAT RIBBON PULL
        |
        TOON, SAVANNAH,10U,GOAT RIBBON PULL
        |
        WALLACE, PAYTON,10U,GOAT RIBBON PULL
        |
        COLCLASURE, RILEY,14U,GOAT RIBBON PULL
        |
        GAMBINO, CORT,14U,GOAT RIBBON PULL
        |
        GILDER, SAVANNAH,14U,GOAT RIBBON PULL
        |
        GOLDEN, CHESNEY,14U,GOAT RIBBON PULL
        |
        LEJEUNE, PRESTON,14U,GOAT RIBBON PULL
        |
        LOGAN, LEXIE,14U,GOAT RIBBON PULL
        |
        MILES, QUINTON,14U,GOAT RIBBON PULL
        |
        PARKS, ZIGGIE,14U,GOAT RIBBON PULL
        |
        SINCLAIR, AVA,14U,GOAT RIBBON PULL
        |
        STEVENS, COPPER,14U,GOAT RIBBON PULL
        |
        TURNER, KOLBY,14U,GOAT RIBBON PULL
        |
        WOOD, AVERY,14U,GOAT RIBBON PULL
        |
        JOHNSON, KAYLEE,14U,GOAT RIBBON PULL
        |
        RAPIEN, MALAKI,14U,GOAT RIBBON PULL
        |
        CRENSHAW, ROWDY,7U,GOAT TYING
        |
        DENNIE, KOOPER,7U,GOAT TYING
        |
        DUFRENE, BENTON,7U,GOAT TYING
        |
        FORTENBERRY, JOE,7U,GOAT TYING
        |
        GLENN, RADLYN,7U,GOAT TYING
        |
        HODGES, DOOLEY,7U,GOAT TYING
        |
        JUAREZ, RAYLEA,7U,GOAT TYING
        |
        LUMMUS, DUB,7U,GOAT TYING
        |
        LUMMUS, LATTIE,7U,GOAT TYING
        |
        POGUE, RIVER,7U,GOAT TYING
        |
        RAGSDALE, HAGEN,7U,GOAT TYING
        |
        STOUT, SUTTON,7U,GOAT TYING
        |
        TOON, BRANTLEY,7U,GOAT TYING
        |
        WILLIAMS, MAGGIE,7U,GOAT TYING
        |
        WILLIAMS, MAVERICK,7U,GOAT TYING
        |
        FLOWERS, AUDREY,7U,GOAT TYING
        |
        VEASLEY, LIVI,7U,GOAT TYING
        |
        DARLING, STRATON,10U,GOAT TYING
        |
        DUFFEY, TATE,10U,GOAT TYING
        |
        FORTENBERRY, ADDISON,10U,GOAT TYING
        |
        HODGES, HARPER,10U,GOAT TYING
        |
        HODGES, WYLIE JO,10U,GOAT TYING
        |
        LEJEUNE, TYESON,10U,GOAT TYING
        |
        MCMURROUGH, RALEIGH,10U,GOAT TYING
        |
        MINTER, HANK,10U,GOAT TYING
        |
        NATION, CHANNING,10U,GOAT TYING
        |
        NATION, JAGGER,10U,GOAT TYING
        |
        PACE, LAYDEN,10U,GOAT TYING
        |
        PARKS, HEATH,10U,GOAT TYING
        |
        SMITH, SAMANTHA,10U,GOAT TYING
        |
        STOUT, SAWYER,10U,GOAT TYING
        |
        SWANSON, BROCK,10U,GOAT TYING
        |
        SWANSON, MADISYN,10U,GOAT TYING
        |
        TALBOTT, BREANNE,10U,GOAT TYING
        |
        TOON, SAVANNAH,10U,GOAT TYING
        |
        WALLACE, PAYTON,10U,GOAT TYING
        |
        COLCLASURE, RILEY,14U,GIRLS GOAT TYING
        |
        CRENSHAW, AUDREY,14U,GIRLS GOAT TYING
        |
        GILDER, SAVANNAH,14U,GIRLS GOAT TYING
        |
        GOLDEN, CHESNEY,14U,GIRLS GOAT TYING
        |
        JORDAN, BROOKLIN,14U,GIRLS GOAT TYING
        |
        LOGAN, LEXIE,14U,GIRLS GOAT TYING
        |
        PARKS, ZIGGIE,14U,GIRLS GOAT TYING
        |
        SINCLAIR, AVA,14U,GIRLS GOAT TYING
        |
        TURNER, KOLBY,14U,GIRLS GOAT TYING
        |
        WOOD, AVERY,14U,GIRLS GOAT TYING
        |
        JOHNSON, KAYLEE,14U,GIRLS GOAT TYING
        |
        GAMBINO, CORT,14U,BOYS GOAT TYING
        |
        LEJEUNE, PRESTON,14U,BOYS GOAT TYING
        |
        MILES, QUINTON,14U,BOYS GOAT TYING
        |
        STEVENS, COPPER,14U,BOYS GOAT TYING
        |
        RAPIEN, MALAKI,14U,BOYS GOAT TYING
        |
        EDDINS, MITCHELL,14U,BOYS GOAT TYING
        |
        EASON, ALLIE,19U,GOAT TYING
        |
        GREEN, HOWARD,19U,GOAT TYING
        |
        TREMBACK, TESSA,19U,GOAT TYING
        |
        BRYANT, MIKIAH,7U,BARRELS
        |
        HODGES, DOOLEY,7U,BARRELS
        |
        JUAREZ, RAYLEA,7U,BARRELS
        |
        LUMMUS, DUB,7U,BARRELS
        |
        LUMMUS, LATTIE,7U,BARRELS
        |
        POGUE, RIVER,7U,BARRELS
        |
        RAWSON, BRYSON,7U,BARRELS
        |
        TINNEY, KESLER,7U,BARRELS
        |
        TOON, BRANTLEY,7U,BARRELS
        |
        WILLIAMS, MAGGIE,7U,BARRELS
        |
        FLOWERS, AUDREY,7U,BARRELS
        |
        JORDAN, ABELLA,7U,BARRELS
        |
        CROW, BRYTIN,7U,BARRELS
        |
        PRICE, WESLEY,7U,BARRELS
        |
        BOULTINGHOUSE, EMMA,10U,BARRELS
        |
        COLLIER, RENI,10U,BARRELS
        |
        HODGES, HARPER,10U,BARRELS
        |
        HODGES, WYLIE JO,10U,BARRELS
        |
        LEJEUNE, TYESON,10U,BARRELS
        |
        MCMURROUGH, RALEIGH,10U,BARRELS
        |
        NATION, CHANNING,10U,BARRELS
        |
        SMITH, SAMANTHA,10U,BARRELS
        |
        TALBOTT, BREANNE,10U,BARRELS
        |
        TINNEY, TURNER,10U,BARRELS
        |
        TOON, SAVANNAH,10U,BARRELS
        |
        WEGLEY, AVA,10U,BARRELS
        |
        WEGLEY, EMMA,10U,BARRELS
        |
        FLOWERS, AVA,10U,BARRELS
        |
        CARPENTER, TYLEE,14U,BARRELS
        |
        COLCLASURE, RILEY,14U,BARRELS
        |
        CORNELL, AVERIE,14U,BARRELS
        |
        FIGUEROA, JONI,14U,BARRELS
        |
        GILDER, SAVANNAH,14U,BARRELS
        |
        GOLDEN, CHESNEY,14U,BARRELS
        |
        LOGAN, LEXIE,14U,BARRELS
        |
        LONG, ASHLYNN,14U,BARRELS
        |
        MILLER, JAYLEE,14U,BARRELS
        |
        PARKS, ZIGGIE,14U,BARRELS
        |
        SINCLAIR, AVA,14U,BARRELS
        |
        TURNER, KOLBY,14U,BARRELS
        |
        WILBURN, JIMMIE JO,14U,BARRELS
        |
        WILLIS, CHLOE,14U,BARRELS
        |
        WOOD, AVERY,14U,BARRELS
        |
        KING, MAYSUN,14U,BARRELS
        |
        RAPIEN, MALAKI,14U,BARRELS
        |
        AKARD, ADDISON,14U,BARRELS
        |
        JONES, JADI,14U,BARRELS
        |
        MCCALLUM, RAVEN,14U,BARRELS
        |
        OGLESBY, BAYLI,14U,BARRELS
        |
        BILLS, REAGAN,19U,BARRELS
        |
        EASON, ALLIE,19U,BARRELS
        |
        GREEN, GRACIE,19U,BARRELS
        |
        GREEN, HOWARD,19U,BARRELS
        |
        HOWELL, AVERY,19U,BARRELS
        |
        ORTIZ, BELLE,19U,BARRELS
        |
        ALLYN, KATE-LEIGH,19U,BARRELS
        |
        EDDINS, RACHEL,19U,BARRELS
        |
        GAMBINO, CORT,14U,CHUTE DOGGING
        |
        MILES, QUINTON,14U,CHUTE DOGGING
        |
        PEEK, TRACE,14U,CHUTE DOGGING
        |
        STEVENS, COPPER,14U,CHUTE DOGGING
        |
        WILLIS, COLE,14U,CHUTE DOGGING
        |
        CARPENTER, COLT,19U,CHUTE DOGGING
        |
        CUNNINGHAM, JASE,19U,CHUTE DOGGING
        |
        CUNNINGHAM, JESS,19U,CHUTE DOGGING
        |
        EASON, ALLIE,19U,CHUTE DOGGING
        |
        GREEN, HOWARD,19U,CHUTE DOGGING
        |
        DENNIE, KOOPER,7U,DUMMY BAW
        |
        DUFRENE, BENTON,7U,DUMMY BAW
        |
        GLENN, RADLYN,7U,DUMMY BAW
        |
        HODGES, DOOLEY,7U,DUMMY BAW
        |
        JONES, BRIGG,7U,DUMMY BAW
        |
        LUMMUS, DODGE,7U,DUMMY BAW
        |
        LUMMUS, DUB,7U,DUMMY BAW
        |
        LUMMUS, LATTIE,7U,DUMMY BAW
        |
        MILLER, JACOB,7U,DUMMY BAW
        |
        MONK, LEVI,7U,DUMMY BAW
        |
        RAGSDALE, HAGEN,7U,DUMMY BAW
        |
        TOON, BRANTLEY,7U,DUMMY BAW
        |
        TOON, WESTON,7U,DUMMY BAW
        |
        WILLIAMS, MAVERICK,7U,DUMMY BAW
        |
        WRIGHT, BRYSON,7U,DUMMY BAW
        |
        PHILLIPS, RYDER,7U,DUMMY BAW
        |
        DARLING, STRATON,10U,DUMMY BAW
        |
        DUFFEY, TATE,10U,DUMMY BAW
        |
        FORTENBERRY, ADDISON,10U,DUMMY BAW
        |
        HODGES, WYLIE JO,10U,DUMMY BAW
        |
        JORDAN, TRENTON,10U,DUMMY BAW
        |
        LEJEUNE, TYESON,10U,DUMMY BAW
        |
        MARTIN, CAIN,10U,DUMMY BAW
        |
        MCMURROUGH, RALEIGH,10U,DUMMY BAW
        |
        MINTER, HANK,10U,DUMMY BAW
        |
        ONEAL, CASH,10U,DUMMY BAW
        |
        ONEAL, LEVI,10U,DUMMY BAW
        |
        PACE, LAYDEN,10U,DUMMY BAW
        |
        PARKS, HEATH,10U,DUMMY BAW
        |
        PHILLIPS, REX,10U,DUMMY BAW
        |
        STOUT, SAWYER,10U,DUMMY BAW
        |
        SWANSON, BROCK,10U,DUMMY BAW
        |
        SWANSON, MADISYN,10U,DUMMY BAW
        |
        TALBOTT, BREANNE,10U,DUMMY BAW
        |
        TOON, SAVANNAH,10U,DUMMY BAW
        |
        WRIGHT, LOGAN,10U,DUMMY BAW
        |
        MONTGOMERY, DEKIN,10U,DUMMY BAW
        |
        NATION, JAGGER,10U,DUMMY BAW
        |
        DARLING, STRATON,10U,BAW
        |
        DUFFEY, TATE,10U,BAW
        |
        GLENN, RADLYN,10U,BAW
        |
        HODGES, WYLIE JO,10U,BAW
        |
        JORDAN, TRENTON,10U,BAW
        |
        LEJEUNE, TYESON,10U,BAW
        |
        LUMMUS, DUB,10U,BAW
        |
        MARTIN, CAIN,10U,BAW
        |
        MCMURROUGH, RALEIGH,10U,BAW
        |
        MINTER, HANK,10U,BAW
        |
        MONK, LEVI,10U,BAW
        |
        ONEAL, CASH,10U,BAW
        |
        ONEAL, LEVI,10U,BAW
        |
        PACE, LAYDEN,10U,BAW
        |
        PARKS, HEATH,10U,BAW
        |
        PHILLIPS, REX,10U,BAW
        |
        STOUT, SAWYER,10U,BAW
        |
        SWANSON, BROCK,10U,BAW
        |
        TOON, SAVANNAH,10U,BAW
        |
        MONTGOMERY, DEKIN,10U,BAW
        |
        TOON, BRANTLEY,10U,BAW
        |
        TINNEY, TURNER,10U,BAW
        |
        CORNELL, AVERIE,14U,GIRLS BREAKAWAY
        |
        CRENSHAW, AUDREY,14U,GIRLS BREAKAWAY
        |
        KING, MAYSUN,14U,GIRLS BREAKAWAY
        |
        MCQUAY, KARRIGAN,14U,GIRLS BREAKAWAY
        |
        PARKS, ZIGGIE,14U,GIRLS BREAKAWAY
        |
        TURNER, KOLBY,14U,GIRLS BREAKAWAY
        |
        WILBURN, JIMMIE JO,14U,GIRLS BREAKAWAY
        |
        WOOD, AVERY,14U,GIRLS BREAKAWAY
        |
        GILDER, SAVANNAH,14U,GIRLS BREAKAWAY
        |
        JOHNSON, KAYLEE,14U,GIRLS BREAKAWAY
        |
        SINCLAIR, AVA,14U,GIRLS BREAKAWAY
        |
        AKARD, ADDISON,14U,GIRLS BREAKAWAY
        |
        FIGUEROA, LANE,14U,BOYS BREAKAWAY
        |
        FIGUEROA, TOBY,14U,BOYS BREAKAWAY
        |
        GAMBINO, CORT,14U,BOYS BREAKAWAY
        |
        LEJEUNE, PRESTON,14U,BOYS BREAKAWAY
        |
        MILES, QUINTON,14U,BOYS BREAKAWAY
        |
        PEEK, TRACE,14U,BOYS BREAKAWAY
        |
        STEVENS, COPPER,14U,BOYS BREAKAWAY
        |
        TALBOTT, BRENDAN,14U,BOYS BREAKAWAY
        |
        WINCHESTER, KOLTAIN,14U,BOYS BREAKAWAY
        |
        MILES, COLBY BUCKSHOT,14U,BOYS BREAKAWAY
        |
        CARPENTER, COLT,19U,BREAKAWAY
        |
        EASON, ALLIE,19U,BREAKAWAY
        |
        GREEN, HOWARD,19U,BREAKAWAY
        |
        ROMANS, WESTON,19U,BREAKAWAY
        |
        WILLIAMS, COLTEN,19U,BREAKAWAY
        |
        BERRY, TREVOR,19U,BREAKAWAY
        |
        TREMBACK, TESSA,19U,BREAKAWAY
        |
        DARLING, STRATON,10U,RIBBON ROPING
        |
        DUFFEY, TATE,10U,RIBBON ROPING
        |
        HODGES, WYLIE JO,10U,RIBBON ROPING
        |
        LEJEUNE, TYESON,10U,RIBBON ROPING
        |
        MCMURROUGH, RALEIGH,10U,RIBBON ROPING
        |
        MINTER, HANK,10U,RIBBON ROPING
        |
        ONEAL, CASH,10U,RIBBON ROPING
        |
        ONEAL, LEVI,10U,RIBBON ROPING
        |
        PARKS, HEATH,10U,RIBBON ROPING
        |
        TOON, SAVANNAH,10U,RIBBON ROPING
        |
        CRENSHAW, AUDREY,14U,RIBBON ROPING
        |
        GAMBINO, CORT,14U,RIBBON ROPING
        |
        MCQUAY, KARRIGAN,14U,RIBBON ROPING
        |
        MILES, QUINTON,14U,RIBBON ROPING
        |
        PARKS, ZIGGIE,14U,RIBBON ROPING
        |
        STEVENS, COPPER,14U,RIBBON ROPING
        |
        CARPENTER, COLT,19U,RIBBON ROPING
        |
        EASON, ALLIE,19U,RIBBON ROPING
        |
        GREEN, HOWARD,19U,RIBBON ROPING
        |
        WILLIAMS, COLTEN,19U,RIBBON ROPING
        |
        DUFFEY, TATE,14U,TIE DOWN
        |
        GAMBINO, CORT,14U,TIE DOWN
        |
        MILES, QUINTON,14U,TIE DOWN
        |
        PEEK, TRACE,14U,TIE DOWN
        |
        STEVENS, COPPER,14U,TIE DOWN
        |
        WINCHESTER, KOLTAIN,14U,TIE DOWN
        |
        MCQUAY, KARRIGAN,14U,TIE DOWN
        |
        CARPENTER, COLT,19U,TIE DOWN
        |
        EASON, ALLIE,19U,TIE DOWN
        |
        GREEN, HOWARD,19U,TIE DOWN
        |
        ROMANS, WESTON,19U,TIE DOWN
        |
        WILLIAMS, COLTEN,19U,TIE DOWN
        |
        BERRY, TREVOR,19U,TIE DOWN
        |
        GOLDEN, GARRETT,19U,TEAM ROPING';
    }
}
