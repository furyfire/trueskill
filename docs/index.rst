Documentation
=============
This is a PHP port of the Moserware.Skills project that's available at

http://github.com/moserware/Skills

For more details on how the algorithm works, see 

http://www.moserware.com/2010/03/computing-your-skill.html

For details on how to use this project, see the accompanying example snippets with this project.

https://www.microsoft.com/en-us/research/project/trueskill-ranking-system/
https://github.com/moserware/PHPSkills
https://www.moserware.com/2010/03/computing-your-skill.html


From Microsoft
--------------
The TrueSkill ranking system is a skill based ranking system for Xbox Live(opens in new tab) developed at Microsoft Research(opens in new tab). The purpose of a ranking system is to both identify and track the skills of gamers in a game (mode) in order to be able to match them into competitive matches. TrueSkill has been used to rank and match players in many different games, from Halo 3 to Forza Motorsport 7(opens in new tab).

An improved version of the TrueSkill ranking system, named TrueSkill 2(opens in new tab), launched with Gears of War 4(opens in new tab) and was later incorporated into Halo 5(opens in new tab).

The classic TrueSkill ranking system only uses the final standings of all teams in a match in order to update the skill estimates (ranks) of all players in the match. The TrueSkill 2 ranking system also uses the individual scores of players in order to weight the contribution of each player to each team. As a result, TrueSkill 2 is much faster at figuring out the skill of a new player.


Links
-----

* `Project README <README.html>`_
* `API Documentations <docs/>`_
* `CodeCoverage <coverage/>`_
* `Test report <test/index.html>`_
* `Mutation testing <mutation/infection.html>`_
* `Code metrics <metrics/index.html>`_
* `Code Standard <CodeStandard.html>`_
* `Benchmark <benchmark.html>`_


Standard Tools
--------------
* PHP8.4

Development Tools
-------------------
* PlantUML
* GraphViz
* Pandoc


PHP Tools
---------
For development Composer and the following packages are used (Recommended as Phars installed via Phive)

* composer install
* composer all