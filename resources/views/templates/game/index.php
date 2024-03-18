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
    let userRole = '<?php echo $role ?>';
    let roles = <?php echo json_encode($roles) ?>;

    let roleToAsk = ['Mafia', 'Doctor', 'Detective'];
    let isUserPlaying = roleToAsk.includes(userRole);
    let mafiaAttacks = null;
    let round = 0;
    let startBtn = document.getElementById('start_game_btn');

    shuffleArray(roles);

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

    let step = -1;

    document.getElementById('phase').innerHTML = 'Phase: Night';

    let doctorSaves = null;
    let chooseVictim = null;
    let newGameRoles = null;
    let victim = null;
    let suspect = null;

    function clickPlayer(self) {
        isUserPlayingAndChoosePlayers(self);
        startNewRound();
    }

    function isUserPlayingAndChoosePlayers(self) {
        setSaveSuspectAndVictim(self);
    }

    function setSaveSuspectAndVictim(self = null) {
        newGameRoles = this.getRolesWithoutSpecificRole();
        chooseVictim = this.getRandomInt(newGameRoles.length);
        victim = newGameRoles[chooseVictim];//newGameRoles[chooseVictim]

        console.log(victim);
        if (roles.includes('Doctor')) {
            let saveIndex = this.getRandomInt(roles.length);
            doctorSaves = roles[saveIndex];
        }

        if (roles.includes('Detective')) {
            let suspectIndex = this.getRandomInt(roles.length);
            suspect = roles[suspectIndex];
        }

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

        if (step === 4) {
            document.getElementById('score_table').innerHTML = '<table id="score_table" style="display: none;"><tr><th>Role</th><th>Score</th></tr></table>';
            document.getElementById('score_table').setAttribute('style', 'display: none;')
            document.getElementById('phase_comment').innerHTML = '';
            document.getElementById('phase').innerHTML = 'Phase: Night';
            document.getElementById('next_round_btn').removeAttribute('disabled');
            step = -1;
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
            if(roles.length === 1) {
                alert('Mafia killed all');
                kickPlayer('mafia');
                return;
            }
            setSaveSuspectAndVictim();
            startNewRound();
        }

        step += 1;

        if (step === 0) {
            document.getElementById('start_game_btn').setAttribute('style', 'display: none;');
            document.getElementById('game_started').setAttribute('style', 'display: block;');
            document.getElementById('next_round_btn').setAttribute('style', 'display: block;');

            if (!victim) {
                newGameRoles = this.getRolesWithoutSpecificRole();
                chooseVictim = this.getRandomInt(newGameRoles.length);
                victim = newGameRoles[chooseVictim];
            }
        }

        initSteps();
    }

    function initSteps() {
        switch (step) {
            case 0:
                this.mafiaStep('Step ' + (step + 1) + ': Mafia choose to attack ' + victim);
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
        console.log({role})
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
        // roles[index]
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

            // get random player
            /*if (! doctorSaves) {
                let playerIndex = this.getRandomInt(roles.length);

                // set randomly player to save
                doctorSaves = roles[playerIndex];
            }*/


            // messag euser
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
                this.createPhaseComment('Step ' + (step + 1) + ': Detective is suspecting that the mafia is player ' + suspect);
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
            createPhaseComment('During the night mafia has attacked player ' + victim);
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
                createPhaseComment('Doctor was able to save ' + victim);
                if (victim === userRole) {
                    alert('You have been saved');
                }
            }, 700);
        } else {
            setTimeout(function () {
                createPhaseComment('And the doctor was unable to save him so Mafia killed the ' + victim);
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

    function checkStatus() {
        let myModal = new bootstrap.Modal(document.getElementById('progressModal'));
        myModal.show();
    }
</script>