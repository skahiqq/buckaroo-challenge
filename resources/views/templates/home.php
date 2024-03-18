<?php
include 'resources/views/partials/navbar.php';
?>
<style>
    #score_table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #score_table td, #score_table th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #score_table tr:nth-child(even){background-color: #f2f2f2;}

    #score_table tr:hover {background-color: #ddd;}

    #score_table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
    }
</style>


<div class="container">
    <div class="card" style="width: 18rem; position:absolute; right: 0;">
        <div class="card-body d-flex align-items-center justify-content-between">
            <p class="card-title" id="phase"></p>
            <button onclick="(startNewRound(), document.getElementById('next_round_btn').setAttribute('disabled', 'disabled'))" class="btn btn-primary" style="display: none" id="next_round_btn">Next round</button>
        </div>
        <div class="card-footer">
            <div id="phase_comment"></div>
        </div>
    </div>
</div>


<div class="container mt-3">
    <div class="row justify-content-center">
        <button class="btn btn-primary" onclick="startNewRound()" id="start_game_btn">Start game</button>
        <span class="text-center" style="display: none" id="game_started">Game started.</span>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-2">
            <table id="score_table" style="display: none">
                <tr>
                    <th>Role</th>
                    <th>Score</th>
                </tr>
            </table>
        </div>
        <div class="col-lg-4">
            <div class="card" style="width: 18rem;" >
                <img src="resources/images/<?php echo strtolower($role); ?>.png" class="card-img-top" alt="...">
                <div class="card-body text-center">
                    <h5 class="card-title">Your Role</h5>
                    <p class="card-text"><?php echo $role; ?> Role</p>
                    <p class="card-text" id="user_choose_attack"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5 game-container">
    <div class="row" id="roles_row">
    </div>
</div>

<script>
    // Get user role
    let userRole = '<?php echo $role ?>';
    // get roles
    let roles = <?php echo json_encode($roles) ?>;

    // role who need to ask for choosing player
    let roleToAsk = ['Mafia', 'Doctor', 'Detective'];
    // if user has role which need to choose player
    let isUserPlaying = roleToAsk.includes(userRole);

    // set round
    let round = 0;
    // start game btn
    let startBtn = document.getElementById('start_game_btn');

    // we mix array keys
    shuffleArray(roles);

    // create foreach role cards except the user role
    roles.forEach((e, index) => {
        if (e !== userRole) {
            this.createRoleCard(e, index);
            if (isUserPlaying) {
                document.getElementById(e).setAttribute('onclick', 'clickPlayer(this)');
                document.getElementById(e).setAttribute('style', 'cursor: pointer;');
                startBtn.innerHTML = 'Please select a player';
                startBtn.onclick = '';
            }
        }
    });

    // set step
    let step = -1;

    // set phase text to night
    document.getElementById('phase').innerHTML = 'Phase: Night';

    // which player doctor saves
    let doctorSaves = null;
    //  we store victim index
    let chooseVictim = null;
    // we store roles without mafia
    let newGameRoles = null;
    // we set role of victim
    let victim = null;
    // we store Detective suspect
    let suspect = null;

    // if user is player ex Mafia, Doctor, Detective we set on click function
    function clickPlayer(self) {
        setSaveSuspectAndVictim(self);
        startNewRound();
    }

    // check if user is playing or not and set doctor save, suspect and victim
    function setSaveSuspectAndVictim(self = null) {
        // get roles without mafia
        newGameRoles = this.getRolesWithoutSpecificRole();
        // choose victim index
        chooseVictim = this.getRandomInt(newGameRoles.length);
        // get victim
        victim = newGameRoles[chooseVictim];

        // if doctor is still alive
        if (roles.includes('Doctor')) {
            let saveIndex = this.getRandomInt(roles.length);
            doctorSaves = roles[saveIndex];
        }

        // if detective is still alive
        if (roles.includes('Detective')) {
            let suspectIndex = this.getRandomInt(roles.length);
            suspect = roles[suspectIndex];
        }

        // if user is player
        if (self) {
            if (roles.includes(userRole)) {
                if (userRole === 'Doctor') {
                    doctorSaves = self.id.charAt(0).toUpperCase() + self.id.slice(1);
                } else if (userRole === 'Mafia') {
                    victim = self.id.charAt(0).toUpperCase() + self.id.slice(1);
                } else if (userRole === 'Detective') {
                    suspect = self.id.charAt(0).toUpperCase() + self.id.slice(1);
                }
            }
        }
    }

    // start new round
    function startNewRound() {
        if (roleToAsk.includes(userRole) && roles.includes(userRole)) {
            roles.forEach((e, index) => {
                if (e !== userRole) {
                    document.getElementById(e).removeAttribute('onclick');
                    document.getElementById(e).removeAttribute('style');
                    startBtn.innerHTML = 'Please select a player';
                    startBtn.onclick = '';
                }
            });
        } else {
            setSaveSuspectAndVictim();
        }

        // if is last step
        if (step === 4) {
            // we set view to start status
            document.getElementById('score_table').innerHTML = '<table id="score_table" style="display: none;"><tr><th>Role</th><th>Score</th></tr></table>';
            document.getElementById('score_table').setAttribute('style', 'display: none;')
            document.getElementById('phase_comment').innerHTML = '';
            document.getElementById('phase').innerHTML = 'Phase: Night';
            document.getElementById('next_round_btn').removeAttribute('disabled');

            // set step to -1
            step = -1;
            // if user is player and is still alive
            if (roleToAsk.includes(userRole) && roles.includes(userRole)) {
                document.getElementById('game_started').innerHTML = 'Pick a player';
                roles.forEach((e, index) => {
                    if (e !== userRole) {
                        document.getElementById(e).setAttribute('onclick', 'clickPlayer(this)');
                        document.getElementById(e).setAttribute('style', 'cursor: pointer;');
                        startBtn.innerHTML = 'Please select a player';
                        startBtn.onclick = '';
                    }
                });
                return;
            }
            // if mafia killed all players
            if(roles.length === 1) {
                alert('Mafia killed all');
                kickPlayer('mafia');
                return;
            }
            // call function if user is playing or not and set doctor save, suspect and victim
            setSaveSuspectAndVictim();
            // start new round
            startNewRound();
        }

        // increase step
        step += 1;

        // if is first step
        if (step === 0) {
            document.getElementById('start_game_btn').setAttribute('style', 'display: none;');
            document.getElementById('game_started').setAttribute('style', 'display: block;');
            document.getElementById('next_round_btn').setAttribute('style', 'display: block;');

            // if we have no victim
            if (!victim) {
                newGameRoles = this.getRolesWithoutSpecificRole();
                chooseVictim = this.getRandomInt(newGameRoles.length);
                victim = newGameRoles[chooseVictim];
            }
        }

        // init steps
        initSteps();
    }

    function initSteps() {
        switch (step) {
            case 0:
                this.mafiaStep('Step ' + (step + 1) + ': Mafia choose to attack Player ' + victim);
                break;
            case 1:
                this.askDoctor();
                break;
            case 2:
                this.askDetective();
                break;
            case 3:
                this.dayPhaseResults();
                break;
            case 4:
                this.voteAndKickPlayer();
                break;
        }
        setTimeout(function () {
            document.getElementById('next_round_btn').removeAttribute('disabled');
        }, (step + 1) * 200);
    }

    function voteAndKickPlayer() {
        // we store score elimination
        let scoreToEliminate = {};
        // get Roles length
        let rolesLength = roles.length;
        // set index 0
        let index = 0;

        let roleToKick = null;

        // set interval to vote each player
        const voteInterval = setInterval(function () {
            // copy roles array and store in tempRoles variable
            let tempRoles = [...roles];
            // remove player to vote
            tempRoles.splice(index, 1);
            // get randomly player to kick
            let playerToKick = this.getRandomInt(roles.length - 1);


            // if player not exist in object of score elimination we set to first vote
            if (scoreToEliminate[roles[playerToKick]] === undefined) {
                scoreToEliminate[roles[playerToKick]] = 1;
            } else {
                // else we increase score +1
                scoreToEliminate[roles[playerToKick]] = scoreToEliminate[roles[playerToKick]] + 1;
            }

            // increase index
            index += 1;

            // if is last player
            if (index === rolesLength) {
                // clear interval
                clearInterval(voteInterval);
                // set Max Value
                let maxValue = 0;

                // foreach keys that we have in score elimination object
                Object.keys(scoreToEliminate).forEach((e, key, index) => {
                    // if player score for elimination is higher than maxValue
                    if (scoreToEliminate[e] > maxValue) {
                        // we set max value to this player
                        maxValue = scoreToEliminate[e];
                        // and we set role to kick
                        roleToKick = e;
                    }

                    // we show stats
                    let createTr = document.createElement("tr");
                    let createtd1 = document.createElement("td");
                    let createtd2 = document.createElement("td");
                    createTr.id = 'tr_' + e;


                    let text1 = document.createTextNode(e);
                    let text2 = document.createTextNode(scoreToEliminate[e]);

                    createTr.appendChild(createtd1);
                    createTr.appendChild(createtd2);
                    createtd1.appendChild(text1);
                    createtd2.appendChild(text2)
                    document.getElementById("score_table").appendChild(createTr);
                });

                document.getElementById('tr_' + roleToKick).setAttribute('style', 'background-color: red');
            }
        }, 100);
        setTimeout(function () {
            createPhaseComment('Player ' + roleToKick + ' has been kicked.');
            if (roleToKick === 'Mafia') {
                alert('They have voted and found mafia. The game will be restarted.');
                window.location.reload();
            }
            if (roleToKick === userRole) {
                alert('You are eliminated');
            }
            kickPlayer(roleToKick);
            document.getElementById("score_table").setAttribute('style', 'display: block;');
        }, 1200)
    }

    function kickPlayer(role) {
        roles.forEach((e, index) => {
            if (e === role) {
                roles.splice(index, 1);
                if (e !== userRole) {
                    document.getElementById('player_' + role + '_title').innerHTML = e;
                    document.getElementById('player_' + role + '_image').src = 'resources/images/' + e.toLowerCase() + '.png';
                    let descriptionOfPlayer =  document.getElementById(role + '_description');
                    descriptionOfPlayer.innerHTML = 'Eliminated';
                    descriptionOfPlayer.setAttribute('style', 'color: red;');
                }
            }
        });
    }

    function createPhaseComment(text) {
        let createSpan = document.createElement("p");
        createSpan.classList = 'pt-1';
        createSpan.appendChild(document.createTextNode(text));
        let br = document.createElement("br");

        document.getElementById("phase_comment").appendChild(createSpan);
        document.getElementById("phase_comment").appendChild(br);
    }

    function getRolesWithoutSpecificRole(roleToExclude = 'Mafia') {
        let tempRoles = [...roles];

        let getMafiaIndex = this.getIndex(roles, roleToExclude);
        tempRoles.splice(getMafiaIndex, 1);


        tempRoles.forEach((e, index) => {
            e = e.toLowerCase();
            if (e === userRole) {
                tempRoles.splice(index, 1);
            }
        });

        return tempRoles;
    }

    function createRoleCard(e, index = null) {
        let createDiv = document.createElement('div');
        createDiv.className = 'col-lg-3 mb-5';
        document.getElementById('roles_row').appendChild(createDiv);
        const key = e;

        createDiv.innerHTML = '<div class="col-lg-3" id="'+ e +'">' +
            '' +
            '<div class="card" style="width: 18rem;"><img src="resources/images/user.png" class="card-img-top" alt="..." id="player_'+ e +'_image"><div class="card-body text-center" id="player_'+ e +'_body">' +
            '<h5 class="card-title" id="player_'+ e +'_title">Player ' + (roles[index]) +'</h5>' +
            '<p class="card-text" id="'+ e +'_description">Player ' + (roles[index])  +'</p>' +
            '</div>' +
            '</div>';
    }

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {

            // Generate random number
            let j = Math.floor(Math.random() * (i + 1));

            let temp = array[i];
            array[i] = array[j];
            array[j] = temp;
        }

        return array;
    }

    function getRandomInt(max) {
        return Math.floor(Math.random() * max);
    }

    function getIndex(array, paramElement) {
        const getElement = (element) => element === paramElement;
        return array.findIndex(getElement);
    }

    function mafiaStep(text) {
        this.createPhaseComment(text);
    }

    function askDoctor() {
        if (roles.includes('Doctor')) {
            setTimeout(function () {
                this.createPhaseComment('Step: ' + (step + 1) + ': Doctor has been asked to save someone');
            }, 100);
            setTimeout(function () {
                this.createPhaseComment('Step: ' + (step + 1) + ': Doctor choose to save: Player ' + doctorSaves);
            }, 200);
        } else {
            startNewRound();
        }
    }

    function askDetective() {
        if (roles.includes('Detective')) {
            setTimeout(function () {
                this.createPhaseComment('Step ' + (step + 1) + ': Detective has a suspect....');
            }, 300);
            setTimeout(function () {
                this.createPhaseComment('Step ' + (step + 1) + ': Detective is suspecting that the mafia is Player ' + suspect);
            }, 400);

            setTimeout(function () {
                if (suspect === 'Mafia') {
                    this.kickPlayer('mafia');
                    alert('Detective found mafia game will be restarted.');
                    window.location.reload();
                    step = -1;
                }
            }, 500);

        } else {
            startNewRound();
        }
    }

    function dayPhaseResults() {
        setTimeout(function () {
            createPhaseComment('During the night mafia has attacked Player ' + victim);
        }, 100);

        // if detective is alive message user
        if (roles.includes('Detective')) {
            setTimeout(function () {
                createPhaseComment('So detective was unable to catch Mafia.');
            }, 600);
        }

        // we message user
        if (doctorSaves === victim) {
            setTimeout(function () {
                createPhaseComment('Doctor was able to save Player ' + victim);
                if (victim === userRole) {
                    alert('You have been saved');
                }
            }, 700);
        } else {
            setTimeout(function () {
                createPhaseComment('And the doctor was unable to save him so Mafia killed the Player ' + victim);
                if (victim === userRole) {
                    alert('You have been eliminated');
                }
                kickPlayer(victim);
            }, 700);

        }


        // message user
        setTimeout(function () {
            createPhaseComment('All of you need to vote who you think is Mafia and that player will be eliminated');
            document.getElementById('phase').innerHTML = 'Phase: Day';
        }, 1000);
    }
</script>