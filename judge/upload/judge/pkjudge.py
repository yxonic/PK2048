#!/usr/bin/python
# -*- coding: utf-8 -*-

import random
import time
from aigame import Player
from aigame import Logger

# For error handling.
class GameError(Exception):
    def __init__(self, msg):
        self.__message = msg
    def __str__(self):
        return self.__message

# Store game state. Player independent.
# Get an input from any player and give the output.
class State:
    def __init__(self):
        self.__mat = []
        for i in range(4):
            self.__mat.append([0, 0, 0, 0])
        self.__mat[1][1] = self.__mat[2][2] = 2
        self.__mat[1][2] = self.__mat[2][1] = 4
        self.__last_gen = ' '.join([str(0), str(0)])
        self.__last_op = ' '.join([str(0), str(0), str(0)])

    def __next(d):
        '''Returns the next block to given direction'''
        dx = (-1, 0, 1, 0)
        dy = (0, 1, 0, -1)
        def ne(x, y):
            if (x + dx[d] in range(4)) and (y + dy[d] in range(4)):
                return x + dx[d], y + dy[d]
            else:
                return None
        return ne

    def __generator(d):
        '''Used for looping. When a direction is set, blocks should be
        handled in a specific order.'''
        path = [[x for x in range(16)],
                [3, 7, 11, 15, 2, 6, 10, 14, 1, 5, 9, 13, 0, 4, 8, 12],
                [15 - x for x in range(16)],
                [0, 4, 8, 12, 1, 5, 9, 13, 2, 6, 10, 14, 3, 7, 11, 15]]
        for x in path[d]:
            yield x // 4, x % 4
        
    def __move(self, d):
        '''Move all blocks to a certain direction. Throws an exception
        when there is no way to go.'''
        score = 0
        valid = False
        ne = self.__class__.__next(d)
        bak = [[0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0]]
        for r, c in self.__class__.__generator(d):
            x, y = r, c
            while ne(x, y):
                xx, yy = ne(x, y)
                if (self.__mat[xx][yy] != 0):
                    break
                self.__mat[xx][yy] = self.__mat[x][y]
                self.__mat[x][y] = 0
                valid = True
                x, y = xx, yy
            if ne(x, y):
                xx, yy = ne(x, y)
                if (self.__mat[xx][yy] == self.__mat[x][y]) and \
                   (bak[xx][yy] == 0):
                    self.__mat[xx][yy] *= 2
                    self.__mat[x][y] = 0
                    bak[xx][yy] = 1
                    score += self.__mat[xx][yy]
                    valid = True
        if not valid:
            raise GameError('Illegal movement!' + str(d + 1))
        return score

    def __put(self, d):
        d -= 1
        x = d // 4
        y = d % 4
        if (x in range(4)) and (y in range(4)) and \
           (self.__mat[x][y] == 0):
            self.__mat[x][y] = 2
        else:
            raise GameError('Illegal placement!')
    
    def __bomb(self, d):
        d -= 1
        x = d // 4
        y = d % 4
        if (x in range(4)) and (y in range(4)):
            score = self.__mat[x][y]
            self.__mat[x][y] = 0
            return score
        else:
            raise GameError('Illegal placement!')

    def __generate(self):
        options = []
        for x in range(16):
            if self.__mat[x // 4][x % 4] == 0:
                options.append(x)
        x = random.choice(options)
        r, c = x // 4, x % 4
        v = random.choice([2, 4])
        self.__mat[r][c] = v
        self.__last_gen = ' '.join([str(x + 1), str(v)])

    def __str__(self):
        last = ' '.join([str(self.__last_gen), self.__last_op])
        state = ' '.join([str(x) for r in self.__mat for x in r])
        return ' '.join([last, state])
    
    def update(self, msg):
        '''Use a given message to update the state. Returns the score
        that the player will get in this round. Illegal movements will
        cause an exception, but rules restricting the use of special
        operations should be handled elsewhere.'''
        if msg[0] != 0:
            self.__put(msg[0])
            self.__last_gen = '0 0'
            self.__last_op = ' '.join([str(x) for x in msg])
            return 0
        if msg[1] != 0:
            self.__bomb(msg[1])
        score = self.__move(msg[2] - 1)
        self.__last_op = ' '.join([str(x) for x in msg])
        self.__generate()
        return score
    
    def blocked(self):
        '''See whether we reached the final state.'''
        nxt = []
        for d in range(4):
            nxt.append(self.__class__.__next(d))
        for x in range(16):
            r = x // 4
            c = x % 4
            if self.__mat[r][c] == 0:
                return False
            if self.__mat[r][c] == 2048:
                return 2048
            for d in range(4):
                if nxt[d](r, c):
                    x, y = nxt[d](r, c)
                    if self.__mat[r][c] == self.__mat[x][y]:
                        return False
        return True

    def print_board(self):
        '''Only for debugging.'''
        for i in range(4):
            for j in range(4):
                print('%5d' % self.__mat[i][j], end='')
            print()
            print()
    
class PKPlayer(Player):
    def start(self, init_msg):
        Player.start(self, init_msg)
        self.put = True
        self.bomb = True
    def check_memory(self):
        pass
    
class Game:
    __players = []
    __win = [0, 0]
    
    def __init__(self, p1, p2, logger):
        self.__players.append(p1)
        self.__players.append(p2)
        self.__logger = logger
        
    def start(self):
        first = random.choice([0, 1])
        second = 1 - first
        for match in range(3):
            self.__logger.log(first)
            self.__players[first].start('1')
            self.__players[second].start('2')
            state = State()
            cur_p = first
            while self.__players[cur_p].bomb or (not state.blocked()):
                last_score = ' '.join([str(self.__players[cur_p].score()), \
                                       str(self.__players[1 - cur_p].score())])
                put = ' '.join([str(state), last_score])
                '''begin_debug
                time.sleep(0.8)
                state.print_board()
                print("Your Score: %d, Enemy's Score: %d" % \
                      (self.__players[0].score(), \
                       self.__players[1].score()))
                print()
                print()
                end_debug'''
                self.__logger.log(str(state) + ' ' + \
                                  str(self.__players[0].score()) + ' ' +\
                                  str(self.__players[1].score()))
                try:
                    self.__players[0].check_memory()
                    self.__players[1].check_memory()
                    text = self.__players[cur_p].query(put)
                    try:
                        msg = [int(x) for x in text.split(' ')]
                    except ValueError:
                        raise GameError('Illegal output!')
                    if len(msg) != 3:
                        raise GameError('Illegal output!')
                    if msg[0] != 0:
                        if not self.__players[cur_p].put:
                            raise GameError('Can\'t put anymore!')
                        if msg[1] != 0 or msg[2] != 0:
                            raise GameError('Illegal output!')
                        self.__players[cur_p].put = False
                    if msg[1] != 0:
                        if not self.__players[cur_p].bomb:
                            raise GameError('Can\'t bomb anymore!')
                        if msg[0] != 0 or msg[2] == 0:
                            raise GameError('Illegal output!')
                        self.__players[cur_p].bomb = False
                    s = state.update(msg)
                    self.__players[cur_p].gain(s)
                except Exception as e:
                    self.__players[cur_p].gain( \
                        -1 - self.__players[cur_p].score())
                    self.__logger.log(e, level=Logger.ERROR)
                    break
                cur_p = 1 - cur_p
                
            s0 = self.__players[0].terminate()
            s1 = self.__players[1].terminate()
            self.__logger.log('-1')
            winner = 0
            if (s0 > s1):
                win = 0
            elif (s0 < s1):
                winner = 1
            else:
                if state.blocked() == 2048:
                    winner = cur_p
                else:
                    winner = 1 - cur_p
            self.__win[winner] += 1
            self.__logger.log(winner)
            first, second = second, first

        self.__logger.log(self.winner())
        self.__logger.close()

    def winner(self):
        if self.__win[0] >= 2:
            return self.__players[0].name()
        else:
            return self.__players[1].name()
